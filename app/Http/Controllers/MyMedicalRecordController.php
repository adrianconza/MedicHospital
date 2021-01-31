<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\ImagingExam;
use App\Models\LaboratoryExam;
use App\Models\MedicalExam;
use App\Models\MedicalRecord;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\Recipe;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MyMedicalRecordController extends Controller
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
     * @return View|RedirectResponse
     * @throws Exception
     */
    public function index(Request $request)
    {
        $patientId = $request->get('patient');
        if (!$request->has('patient') || $patientId === null) {
            return redirect()->route('client.myPatient.index');
        }

        $patient = Patient::find($patientId);
        $medicalRecords = MedicalRecord::whereHas('appointment', function ($q) use ($patientId) {
            $q->where('patient_id', $patientId);
        })->orderBy('created_at')->paginate(10);
        return view('client.myMedicalRecord.index', compact('patient', 'medicalRecords'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @param Request $request
     * @return View|RedirectResponse
     */
    public function show(int $id, Request $request)
    {
        $patientId = $request->get('patient');
        if (!$request->has('patient') || $patientId === null) {
            return redirect()->route('client.myPatient.index');
        }

        $medicalRecord = MedicalRecord::find($id);
        $patient = Patient::find($patientId);
        $resultEnum = MedicalExam::RESULTS;
        $unitEnum = Recipe::UNITS;
        return view('client.myMedicalRecord.show', compact('medicalRecord', 'patient', 'resultEnum', 'unitEnum'));
    }
}
