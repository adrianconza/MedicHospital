<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
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
        $startDay = $dateAppointment->startOfDay()->toDateTimeString();
        $endDay = $dateAppointment->endOfDay()->toDateTimeString();
        $appointments = null;
        if ($request->has('patient_search') && $patientSearch !== null) {
            $appointments = Appointment::where('start_time', '>=', $startDay)->where('start_time', '<=', $endDay)->where('user_id', Auth::id())->where('patient_id', $patientSearch)->orderBy('start_time')->paginate(10);
        }
        if (!$appointments) {
            $appointments = Appointment::where('start_time', '>=', $startDay)->where('start_time', '<=', $endDay)->where('user_id', Auth::id())->orderBy('start_time')->paginate(10);
        }

        $patients = Patient::orderBy('name')->get();
        return view('doctor.schedule.index', compact('patients', 'appointments', 'daySearch', 'patientSearch'));
    }
}
