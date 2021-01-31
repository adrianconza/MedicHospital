<?php

namespace App\Http\Controllers;

use App\Models\LaboratoryExam;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LaboratoryExamController extends Controller
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
        if ($request->has('search') && $searchValue !== null) {
            $laboratoryExams = LaboratoryExam::search($searchValue)->paginate(10)->withQueryString();
        } else {
            $laboratoryExams = LaboratoryExam::withTrashed()->orderBy('name')->paginate(10);
        }
        return view('admin.laboratoryExam.index', compact('laboratoryExams', 'searchValue'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return view('admin.laboratoryExam.create');
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
            'name' => 'bail|required|min:5|max:100|unique:laboratory_exams|alpha_spaces|string'
        ]);
        LaboratoryExam::create($request->all());
        return redirect()->route('admin.laboratoryExam.index');
    }

    /**
     * Display the specified resource.
     *
     * @param LaboratoryExam $laboratoryExam
     * @return View
     */
    public function show(LaboratoryExam $laboratoryExam)
    {
        return view('admin.laboratoryExam.show', compact('laboratoryExam'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param LaboratoryExam $laboratoryExam
     * @return View
     */
    public function edit(LaboratoryExam $laboratoryExam)
    {
        return view('admin.laboratoryExam.edit', compact('laboratoryExam'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param LaboratoryExam $laboratoryExam
     * @return RedirectResponse
     */
    public function update(Request $request, LaboratoryExam $laboratoryExam)
    {
        $request->validate([
            'name' => "bail|required|min:5|max:100|unique:laboratory_exams,name,$laboratoryExam->id|alpha_spaces|string"
        ]);
        $laboratoryExam->update($request->all());
        return redirect()->route('admin.laboratoryExam.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param LaboratoryExam $laboratoryExam
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(LaboratoryExam $laboratoryExam)
    {
        $laboratoryExam->delete();
        return redirect()->route('admin.laboratoryExam.index');
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function restore(int $id)
    {
        LaboratoryExam::withTrashed()->where('id', $id)->restore();
        return redirect()->route('admin.laboratoryExam.index');
    }
}
