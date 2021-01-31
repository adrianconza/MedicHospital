<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\ImagingExam;
use App\Models\LaboratoryExam;
use App\Models\MedicalExam;
use App\Models\MedicalRecord;
use App\Models\Medicine;
use App\Models\Recipe;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
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
     * @return View|RedirectResponse
     * @throws Exception
     */
    public function index(Request $request)
    {
        $appointmentId = $request->get('appointment');
        if (!$request->has('appointment') || $appointmentId === null) {
            return redirect()->route('doctor.schedule.index');
        }

        $appointment = Appointment::find($appointmentId);
        if (!$appointment->validTime()) {
            return redirect()->route('doctor.schedule.index');
        }

        $medicalRecords = MedicalRecord::whereHas('appointment', function ($q) use ($appointment) {
            $q->where('patient_id', $appointment->patient_id);
        })->orderBy('created_at')->paginate(10);
        return view('doctor.medicalRecord.index', compact('appointment', 'medicalRecords'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return View|RedirectResponse
     */
    public function create(Request $request)
    {
        $appointmentId = $request->get('appointment');
        if (!$request->has('appointment') || $appointmentId === null) {
            return redirect()->route('doctor.schedule.index');
        }

        $appointment = Appointment::find($appointmentId);
        if (!$appointment->validTime()) {
            return redirect()->route('doctor.schedule.index');
        }

        $unitEnum = Recipe::UNITS;
        $patient = $appointment->patient;
        $medicines = Medicine::orderBy('name')->get();
        $laboratoryExams = LaboratoryExam::orderBy('name')->get();
        $imagingExams = ImagingExam::orderBy('name')->get();
        $exams = null;
        foreach ($laboratoryExams as $laboratoryExam) {
            $exams[] = (object)[
                'id' => "LE-{$laboratoryExam->id}",
                'name' => "Examen de laboratorio - {$laboratoryExam->name}",
            ];
        }
        foreach ($imagingExams as $imagingExam) {
            $exams[] = (object)[
                'id' => "IE-{$imagingExam->id}",
                'name' => "Examen de imagen - {$imagingExam->name}",
            ];
        }
        return view('doctor.medicalRecord.create', compact('appointment', 'patient', 'medicines', 'exams', 'unitEnum'));
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
        if (!$request->has('appointment') || $appointmentId === null) {
            return redirect()->route('doctor.schedule.index');
        }

        $appointment = Appointment::find($appointmentId);
        if (!$appointment->validTime()) {
            return redirect()->route('doctor.schedule.index');
        }

        $request->validate([
            'diagnosis' => 'bail|required|min:5|max:500|string'
        ]);

        $medicalRecord = new MedicalRecord();
        $medicalRecord->fill($request->all());
        $medicalRecord->appointment()->associate($appointment);
        $medicalRecord->save();

        $amounts = $request->get('amounts');
        $units = $request->get('units');
        $medicines = $request->get('medicines');
        $prescriptions = $request->get('prescriptions');
        if ($medicines) {
            for ($i = 0; $i < count($medicines); $i++) {
                $medicine = Medicine::find($medicines[$i]);
                $recipe = new Recipe();
                $recipe->fill([
                    'amount' => $amounts[$i],
                    'unit' => $units[$i],
                    'prescription' => $prescriptions[$i],
                ]);
                $recipe->medicine()->associate($medicine);
                $recipe->medicalRecord()->associate($medicalRecord);
                $recipe->save();
            }
        }

        $exams = $request->get('exams');
        if ($exams) {
            foreach ($exams as $exam) {
                $medicalExam = new MedicalExam();

                if (str_contains($exam, 'LE-')) {
                    $laboratoryExam = LaboratoryExam::find(str_replace('LE-', '', $exam));
                    $medicalExam->laboratoryExam()->associate($laboratoryExam);
                }

                if (str_contains($exam, 'IE-')) {
                    $imagingExam = ImagingExam::find(str_replace('IE-', '', $exam));
                    $medicalExam->imagingExam()->associate($imagingExam);
                }

                $medicalExam->medicalRecord()->associate($medicalRecord);
                $medicalExam->save();
            }
        }

        return redirect()->route('doctor.medicalRecord.index', ['appointment' => $appointmentId]);
    }

    /**
     * Display the specified resource.
     *
     * @param MedicalRecord $medicalRecord
     * @param Request $request
     * @return View|RedirectResponse
     */
    public function show(MedicalRecord $medicalRecord, Request $request)
    {
        $appointmentId = $request->get('appointment');
        if (!$request->has('appointment') || $appointmentId === null) {
            return redirect()->route('doctor.schedule.index');
        }

        $appointment = Appointment::find($appointmentId);
        if (!$appointment->validTime()) {
            return redirect()->route('doctor.schedule.index');
        }

        $resultEnum = MedicalExam::RESULTS;
        $unitEnum = Recipe::UNITS;
        return view('doctor.medicalRecord.show', compact('medicalRecord', 'appointment', 'resultEnum', 'unitEnum'));
    }
}
