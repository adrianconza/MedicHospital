<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\MedicalSpeciality;
use App\Models\Patient;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use DateInterval;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{

    private $appointmentTime = 30;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $appointments = Appointment::where('start_time', '>=', Carbon::now())->orderBy('start_time')->paginate(10);
        return view('admin.appointment.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return View
     */
    public function create(Request $request)
    {
        $dayAppointment = $request->get('day_appointment');
        $medicalSpecialityId = $request->get('medical_speciality');
        $appointments = null;
        $patients = null;

        if ($request->has('day_appointment') && $dayAppointment !== null && $request->has('medical_speciality') && $medicalSpecialityId !== null) {
            $medicalSpeciality = MedicalSpeciality::find($medicalSpecialityId);
            $dateAppointment = new Carbon($dayAppointment);

            $doctors = DB::select('select u.id
                from users u
                inner join role_user ru on u.id = ru.user_id
                inner join roles r on ru.role_id = r.id
                inner join medical_speciality_user msu on u.id = msu.user_id
                inner join medical_specialities ms on msu.medical_speciality_id = ms.id
                where r.name = :role and ru.deleted_at is null and ms.id = :medical_speciality_id',
                ['role' => Role::DOCTOR, 'medical_speciality_id' => $medicalSpeciality->id]);
            foreach ($doctors as $clave => $valor) {
                $doctor = User::find($valor->id);

                $startDay = $dateAppointment->startOfDay()->toDateTimeString();
                $endDay = $dateAppointment->endOfDay()->toDateTimeString();
                $doctorAppointments = DB::select('select a.start_time, a.end_time
                    from appointments a
                    inner join users u on u.id = a.user_id
                    where a.deleted_at is null and u.id = :user_id and a.start_time >= :start_day and a.start_time <= :end_day
                    order by start_time',
                    ['user_id' => $doctor->id, 'start_day' => $startDay, 'end_day' => $endDay]);

                foreach ($doctor->attentionSchedules as $attentionSchedule) {
                    $startTime = new Carbon($attentionSchedule->start_time);
                    $startTime->year = $dateAppointment->year;
                    $startTime->month = $dateAppointment->month;
                    $startTime->day = $dateAppointment->day;
                    $endTime = new Carbon($attentionSchedule->end_time);
                    $endTime->year = $dateAppointment->year;
                    $endTime->month = $dateAppointment->month;
                    $endTime->day = $dateAppointment->day;
                    $diffInMinutes = $startTime->diffInMinutes($endTime);
                    for ($i = 0; $i < $diffInMinutes / $this->appointmentTime; $i++) {
                        $minutesAdd = $this->appointmentTime * $i;
                        $newAppointmentStartTime = $startTime->copy()->add(new DateInterval("PT{$minutesAdd}M"));
                        $newAppointmentEndTime = $newAppointmentStartTime->copy()->add(new DateInterval("PT{$this->appointmentTime}M"));

                        $existAppointment = false;
                        foreach ($doctorAppointments as $doctorAppointmentClave => $doctorAppointmentValor) {
                            $appointmentStartTime = new Carbon($doctorAppointmentValor->start_time);
                            $appointmentEndTime = new Carbon($doctorAppointmentValor->end_time);
                            if ($appointmentStartTime->eq($newAppointmentStartTime) && $appointmentEndTime->eq($newAppointmentEndTime)) {
                                $existAppointment = true;
                                break;
                            }
                        }

                        if (!$existAppointment && $newAppointmentStartTime->gte(Carbon::now())) {
                            $appointments[] = (object)[
                                'start_time' => $newAppointmentStartTime,
                                'end_time' => $newAppointmentEndTime,
                                'duration' => $this->appointmentTime,
                                'doctor_id' => $doctor->id,
                                'doctor' => "{$doctor->name} {$doctor->last_name}",
                                'medical_speciality_id' => $medicalSpeciality->id,
                                'medical_speciality' => $medicalSpeciality->name,
                            ];
                        }
                    }
                }
            }
            if ($appointments) {
                array_multisort(array_column($appointments, 'start_time'), SORT_ASC,
                    array_column($appointments, 'doctor'), SORT_ASC,
                    $appointments);
                $patients = Patient::all();
            }
        }

        $medicalSpecialities = MedicalSpeciality::orderBy('name')->get();
        return view('admin.appointment.create', compact('medicalSpecialities', 'dayAppointment', 'medicalSpecialityId', 'appointments', 'patients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'start_time' => 'bail|required|after:today|date',
            'end_time' => 'bail|required|after:start_date|date',
            'reason' => 'bail|required|min:5|max:500|string',
            'medical_speciality' => 'bail|required|numeric',
            'doctor' => 'bail|required',
            'patient' => 'bail|required',
        ]);
        $medicalSpeciality = MedicalSpeciality::find($request->medical_speciality);
        $doctor = User::find($request->doctor);
        $patient = Patient::find($request->patient);
        $appointment = new Appointment();
        $appointment->fill($request->all());
        $duration = Carbon::now();
        $duration->hour = 0;
        $duration->minute = $this->appointmentTime;
        $duration->second = 0;
        $appointment->duration = $duration;
        $appointment->patient()->associate($patient);
        $appointment->medicalSpeciality()->associate($medicalSpeciality);
        $appointment->user()->associate($doctor);
        $appointment->save();
        return redirect()->route('admin.appointment.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Appointment $appointment
     * @return View
     */
    public function show(Appointment $appointment)
    {
        return view('admin.appointment.show', compact('appointment'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Appointment $appointment
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Appointment $appointment)
    {
        if ((new Carbon($appointment->start_time))->lte(Carbon::now())) {
            return redirect()->route('admin.appointment.index');
        }

        $appointment->delete();
        return redirect()->route('admin.appointment.index');
    }
}
