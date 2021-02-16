<?php

namespace App\Http\Controllers;

use;
use App\Models\Appointment;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
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
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        $daySearch = $request->has('day_search') && $request->get('day_search') ? $request->get('day_search') : date('Y-m-d');
        $patientSearch = $request->get('patient_search');
        $dateAppointment = new Carbon($daySearch);
        if ($dateAppointment->lt(Carbon::today())) {
            $daySearch = date('Y-m-d');
            $dateAppointment = new Carbon($daySearch);
        }
        if ($dateAppointment->eq(Carbon::today())) {
            $now = Carbon::now();
            $dateAppointment->micro = $now->micro;
            $dateAppointment->second = $now->second;
            $dateAppointment->minute = $now->minute;
            $dateAppointment->hour = $now->hour;
        }
        $startDate = $dateAppointment->clone()->subMinutes(Appointment::EXTRA_TIME)->toDateTimeString();
        $endDate = $dateAppointment->endOfDay()->toDateTimeString();
        $appointments = null;
        if ($request->has('patient_search') && $patientSearch !== null) {
            $appointments = Appointment::where('end_time', '>=', $startDate)->where('start_time', '<=', $endDate)->where('user_id', Auth::id())->where('patient_id', $patientSearch)->orderBy('start_time')->paginate(10);
        }
        if (!$appointments) {
            $appointments = Appointment::where('end_time', '>=', $startDate)->where('start_time', '<=', $endDate)->where('user_id', Auth::id())->orderBy('start_time')->paginate(10);
        }

        $patients = Patient::orderBy('name')->get();
        return view('doctor.schedule.index', compact('patients', 'appointments', 'daySearch', 'patientSearch'));
    }
}
