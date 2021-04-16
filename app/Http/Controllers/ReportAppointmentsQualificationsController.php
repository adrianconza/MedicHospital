<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Contracts\View\View;

class ReportAppointmentsQualificationsController extends Controller
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
     * @return View
     */
    public function index()
    {
        $report = Appointment::generateReportAppointmentsAndQualifications();
        return view('admin.reportAppointmentsQualifications.index', compact('report'));
    }
}
