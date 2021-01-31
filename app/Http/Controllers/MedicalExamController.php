<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\MedicalExam;
use App\Models\Medicine;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MedicalExamController extends Controller
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
        $searchValue = $request->get('search');
        $medicalExams = MedicalExam::medicalExamsWithoutResult($searchValue);
        return view('admin.medicalExam.index', compact('medicalExams', 'searchValue'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $appointmentId
     * @return View
     */
    public function edit(int $appointmentId)
    {
        $medicalExams = MedicalExam::medicalExamsWithoutResultAndAppointmentId($appointmentId);
        $resultsEnum = MedicalExam::RESULTS;
        $patient = Appointment::find($appointmentId)->patient;
        return view('admin.medicalExam.edit', compact('medicalExams', 'appointmentId', 'resultsEnum', 'patient'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Medicine $medicine
     * @return RedirectResponse
     */
    public function update(Request $request, Medicine $medicine)
    {
        $request->validate([
            'ids' => 'bail|required|present|array',
            'results' => 'bail|required|present|array',
        ]);

        $ids = $request->get('ids');
        $results = $request->get('results');
        if ($ids) {
            for ($i = 0; $i < count($ids); $i++) {
                $medicalExam = MedicalExam::find($ids[$i]);
                $medicalExam->result = $results[$i];
                $medicalExam->save();
            }
        }

        return redirect()->route('admin.medicalExam.index');
    }
}
