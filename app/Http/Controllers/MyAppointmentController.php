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
use Illuminate\Support\Facades\Auth;

class MyAppointmentController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('client');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        $patientSearch = $request->get('patient_search');
        $patients = Patient::whereHas('users', function ($q) {
            $q->where('users.id', Auth::id());
        })->orderBy('name')->get();
        $appointments = null;

        if ($request->has('patient_search') && $patientSearch !== null) {
            $appointments = Appointment::where('end_time', '>=', Carbon::now())->where('patient_id', $patientSearch)->orderBy('start_time')->paginate(10);
        }
        if (!$appointments) {
            $appointments = Appointment::where('end_time', '>=', Carbon::now())->whereIn('patient_id', $patients->pluck('id')->toArray())->orderBy('start_time')->paginate(10);
        }

        return view('client.myAppointment.index', compact('patients', 'appointments', 'patientSearch'));
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
            $patients = Patient::whereHas('users', function ($q) {
                $q->where('users.id', Auth::id());
            })->orderBy('name')->get();
        }
        $medicalSpecialities = MedicalSpeciality::orderBy('name')->get();
        return view('client.myAppointment.create', compact('medicalSpecialities', 'dayAppointment', 'medicalSpecialityId', 'doctors', 'doctorId', 'appointments', 'patients'));
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
        return redirect()->route('client.myAppointment.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return View
     */
    public function show(int $id)
    {
        $appointment = Appointment::find($id);
        return view('client.myAppointment.show', compact('appointment'));
    }
}
