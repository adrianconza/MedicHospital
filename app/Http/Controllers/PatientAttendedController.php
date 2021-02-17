<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientAttendedController extends Controller
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
        $searchValue = $request->get('search');
        if ($request->has('search') && $searchValue !== null) {
            $patients = Patient::search($searchValue)->whereHas('appointments', function ($q) {
                $q->where('appointments.user_id', Auth::id());
            })->paginate(10)->withQueryString();
        } else {
            $patients = Patient::withTrashed()->whereHas('appointments', function ($q) {
                $q->where('appointments.user_id', Auth::id());
            })->orderBy('name')->paginate(10);
        }
        return view('doctor.patientAttended.index', compact('patients', 'searchValue'));
    }
}
