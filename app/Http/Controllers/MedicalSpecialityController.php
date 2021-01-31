<?php

namespace App\Http\Controllers;

use App\Models\MedicalSpeciality;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MedicalSpecialityController extends Controller
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
            $medicalSpecialities = MedicalSpeciality::search($searchValue)->paginate(10)->withQueryString();
        } else {
            $medicalSpecialities = MedicalSpeciality::withTrashed()->orderBy('name')->paginate(10);
        }
        return view('admin.medicalSpeciality.index', compact('medicalSpecialities', 'searchValue'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return view('admin.medicalSpeciality.create');
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
            'name' => 'bail|required|min:5|max:100|unique:medical_specialities|alpha_spaces|string'
        ]);
        MedicalSpeciality::create($request->all());
        return redirect()->route('admin.medicalSpeciality.index');
    }

    /**
     * Display the specified resource.
     *
     * @param MedicalSpeciality $medicalSpeciality
     * @return View
     */
    public function show(MedicalSpeciality $medicalSpeciality)
    {
        return view('admin.medicalSpeciality.show', compact('medicalSpeciality'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param MedicalSpeciality $medicalSpeciality
     * @return View
     */
    public function edit(MedicalSpeciality $medicalSpeciality)
    {
        return view('admin.medicalSpeciality.edit', compact('medicalSpeciality'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param MedicalSpeciality $medicalSpeciality
     * @return RedirectResponse
     */
    public function update(Request $request, MedicalSpeciality $medicalSpeciality)
    {
        $request->validate([
            'name' => "bail|required|min:5|max:100|unique:medical_specialities,name,$medicalSpeciality->id|alpha_spaces|string"
        ]);
        $medicalSpeciality->update($request->all());
        return redirect()->route('admin.medicalSpeciality.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param MedicalSpeciality $medicalSpeciality
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(MedicalSpeciality $medicalSpeciality)
    {
        $medicalSpeciality->delete();
        return redirect()->route('admin.medicalSpeciality.index');
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function restore(int $id)
    {
        MedicalSpeciality::withTrashed()->where('id', $id)->restore();
        return redirect()->route('admin.medicalSpeciality.index');
    }
}
