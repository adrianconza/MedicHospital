<?php

namespace App\Http\Controllers;

use App\Models\ImagingExam;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ImagingExamController extends Controller
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
            $imagingExams = ImagingExam::search($searchValue)->paginate(10)->withQueryString();
        } else {
            $imagingExams = ImagingExam::withTrashed()->orderBy('name')->paginate(10);
        }
        return view('admin.imagingExam.index', compact('imagingExams', 'searchValue'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return view('admin.imagingExam.create');
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
            'name' => 'bail|required|min:5|max:100|unique:imaging_exams|alpha_spaces|string'
        ]);
        ImagingExam::create($request->all());
        return redirect()->route('admin.imagingExam.index');
    }

    /**
     * Display the specified resource.
     *
     * @param ImagingExam $imagingExam
     * @return View
     */
    public function show(ImagingExam $imagingExam)
    {
        return view('admin.imagingExam.show', compact('imagingExam'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ImagingExam $imagingExam
     * @return View
     */
    public function edit(ImagingExam $imagingExam)
    {
        return view('admin.imagingExam.edit', compact('imagingExam'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param ImagingExam $imagingExam
     * @return RedirectResponse
     */
    public function update(Request $request, ImagingExam $imagingExam)
    {
        $request->validate([
            'name' => "bail|required|min:5|max:100|unique:imaging_exams,name,$imagingExam->id|alpha_spaces|string"
        ]);
        $imagingExam->update($request->all());
        return redirect()->route('admin.imagingExam.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ImagingExam $imagingExam
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(ImagingExam $imagingExam)
    {
        $imagingExam->delete();
        return redirect()->route('admin.imagingExam.index');
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function restore(int $id)
    {
        ImagingExam::withTrashed()->where('id', $id)->restore();
        return redirect()->route('admin.imagingExam.index');
    }
}
