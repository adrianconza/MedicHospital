<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\MedicalSpeciality;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NextAppointmentController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('doctor');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return View|RedirectResponse
     * @throws Exception
     */
    public function create(Request $request)
    {
        $appointmentId = $request->get('appointment');
        $patientId = $request->get('patient');
        if (!$request->has('appointment') || $appointmentId === null || !$request->has('patient') || $patientId === null) {
            return redirect()->route('doctor.schedule.index');
        }

        $appointment = Appointment::find($appointmentId);
        if (!$appointment->validTime()) {
            return redirect()->route('doctor.schedule.index');
        }

        $dayAppointment = $request->has('day_appointment') && $request->get('day_appointment') ? $request->get('day_appointment') : date('Y-m-d');
        $medicalSpecialityId = $request->get('medical_speciality');
        $doctorId = $request->get('doctor');
        $appointments = null;
        $doctors = null;

        $dateAppointment = new Carbon($dayAppointment);
        if ($dateAppointment->lt(Carbon::today())) {
            $dayAppointment = date('Y-m-d');
        }

        if ($request->has('day_appointment') && $dayAppointment !== null && $request->has('medical_speciality') && $medicalSpecialityId !== null) {
            $medicalSpeciality = MedicalSpeciality::find($medicalSpecialityId);
            $doctors = User::doctorsByMedicalSpeciality($medicalSpeciality->id);

            if ($doctorId) {
                $appointments = Appointment::generateDoctorAppointments($dayAppointment, $doctorId, $medicalSpeciality);
            } else {
                $appointments = Appointment::generateAppointments($dayAppointment, $medicalSpeciality);
            }
        }

        $patient = Patient::find($patientId);
        $medicalSpecialities = MedicalSpeciality::orderBy('name')->get();
        return view('doctor.nextAppointment.create', compact('appointmentId', 'patient', 'medicalSpecialities', 'dayAppointment', 'medicalSpecialityId', 'doctors', 'doctorId', 'appointments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $appointmentId = $request->get('appointment');
        $patientId = $request->get('patient');
        if (!$request->has('appointment') || $appointmentId === null || !$request->has('patient') || $patientId === null) {
            return redirect()->route('doctor.schedule.index');
        }

        $appointmentSchedule = Appointment::find($appointmentId);
        if (!$appointmentSchedule->validTime()) {
            return redirect()->route('doctor.schedule.index');
        }

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
        $patient = Patient::find($patientId);
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
        return redirect()->route('doctor.medicalRecord.index', ['appointment' => $appointmentId]);
    }
}
