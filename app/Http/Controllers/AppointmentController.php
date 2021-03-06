<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\MedicalSpeciality;
use App\Models\Patient;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('administrator');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        $doctorSearch = $request->get('doctor_search');
        $patientSearch = $request->get('patient_search');
        $appointments = null;
        if ($request->has('doctor_search') && $doctorSearch !== null) {
            $appointments = Appointment::where('start_time', '>=', Carbon::now())->where('user_id', $doctorSearch)->orderBy('start_time')->paginate(10);
        }
        if ($request->has('patient_search') && $patientSearch !== null) {
            $appointments = Appointment::where('start_time', '>=', Carbon::now())->where('patient_id', $patientSearch)->orderBy('start_time')->paginate(10);
        }
        if (!$appointments) {
            $appointments = Appointment::where('start_time', '>=', Carbon::now())->orderBy('start_time')->paginate(10);
        }

        $patients = Patient::orderBy('name')->get();
        $doctors = User::whereHas('roles', function ($q) {
            $q->where('name', Role::DOCTOR);
        })->orderBy('name')->get();
        $appointmentTypeEnum = Appointment::TYPE;
        return view('admin.appointment.index', compact('patients', 'doctors', 'appointments', 'doctorSearch', 'patientSearch', 'appointmentTypeEnum'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return View
     * @throws Exception
     */
    public function create(Request $request)
    {
        $dayAppointment = $request->has('day_appointment') && $request->get('day_appointment') ? $request->get('day_appointment') : date('Y-m-d');
        $medicalSpecialityId = $request->get('medical_speciality');
        $doctorId = $request->get('doctor');
        $appointments = null;
        $patients = null;
        $doctors = null;

        $dateAppointment = new Carbon($dayAppointment);
        if ($dateAppointment->lt(Carbon::today())) {
            $dayAppointment = date('Y-m-d');
        }

        if ($request->has('day_appointment') && $request->has('medical_speciality') && $medicalSpecialityId !== null) {
            $medicalSpeciality = MedicalSpeciality::find($medicalSpecialityId);
            $doctors = User::doctorsByMedicalSpeciality($medicalSpeciality->id);

            if ($doctorId) {
                $appointments = Appointment::generateDoctorAppointments($dayAppointment, $doctorId, $medicalSpeciality);
            } else {
                $appointments = Appointment::generateAppointments($dayAppointment, $medicalSpeciality);
            }
        }

        if ($appointments) {
            $patients = Patient::all();
        }
        $medicalSpecialities = MedicalSpeciality::orderBy('name')->get();
        return view('admin.appointment.create', compact('medicalSpecialities', 'dayAppointment', 'medicalSpecialityId', 'doctors', 'doctorId', 'appointments', 'patients'));
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
        $duration->minute = Appointment::TIME;
        $duration->second = 0;
        $appointment->duration = $duration;
        $appointment->patient()->associate($patient);
        $appointment->medicalSpeciality()->associate($medicalSpeciality);
        $appointment->user()->associate($doctor);
        $appointment->save();
        return redirect()->route('admin.appointment.index');
    }

    /**
     * Store a extra shift.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function storeExtraShift(Request $request)
    {
        $request->validate([
            'day_appointment' => 'bail|required|after_or_equal:today|date',
            'start_time' => 'bail|required',
            'reason' => 'bail|required|min:5|max:500|string',
            'medical_speciality' => 'bail|required|numeric',
            'doctor' => 'bail|required',
            'patient' => 'bail|required',
        ]);

        $startTime = new Carbon($request->day_appointment . $request->start_time);
        $endTime = $startTime->copy()->addMinutes(Appointment::TIME);

        if ($startTime->lt(Carbon::now())) {
            return redirect()->route('admin.appointment.create', ['day_appointment' => $request->day_appointment, 'medical_speciality' => $request->medical_speciality, 'doctor' => $request->doctor]);
        }

        $existAppointment = Appointment::validateExistAppointment($request->doctor, $startTime, $endTime);
        if ($existAppointment) {
            return redirect()->route('admin.appointment.create', ['day_appointment' => $request->day_appointment, 'medical_speciality' => $request->medical_speciality, 'doctor' => $request->doctor]);
        }

        $extraShift = Appointment::validateExtraShift($request->doctor, $startTime);
        if (!$extraShift) {
            return redirect()->route('admin.appointment.create', ['day_appointment' => $request->day_appointment, 'medical_speciality' => $request->medical_speciality, 'doctor' => $request->doctor]);
        }

        $medicalSpeciality = MedicalSpeciality::find($request->medical_speciality);
        $doctor = User::find($request->doctor);
        $patient = Patient::find($request->patient);
        $appointment = new Appointment();
        $appointment->fill($request->all());
        $appointment->start_time = $startTime;
        $appointment->end_time = $endTime;
        $duration = Carbon::now();
        $duration->hour = 0;
        $duration->minute = Appointment::TIME;
        $duration->second = 0;
        $appointment->duration = $duration;
        $appointment->type = Appointment::EXTRA_SHIFT;
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
        $appointmentTypeEnum = Appointment::TYPE;
        return view('admin.appointment.show', compact('appointment', 'appointmentTypeEnum'));
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
