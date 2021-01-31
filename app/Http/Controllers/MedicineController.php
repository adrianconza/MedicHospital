<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MedicineController extends Controller
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
            $medicines = Medicine::search($searchValue)->paginate(10)->withQueryString();
        } else {
            $medicines = Medicine::withTrashed()->orderBy('name')->paginate(10);
        }
        return view('admin.medicine.index', compact('medicines', 'searchValue'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return view('admin.medicine.create');
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
        Medicine::create($request->all());
        return redirect()->route('admin.medicine.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Medicine $medicine
     * @return View
     */
    public function show(Medicine $medicine)
    {
        return view('admin.medicine.show', compact('medicine'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Medicine $medicine
     * @return View
     */
    public function edit(Medicine $medicine)
    {
        return view('admin.medicine.edit', compact('medicine'));
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
            'name' => "bail|required|min:5|max:100|unique:medical_specialities,name,$medicine->id|alpha_spaces|string"
        ]);
        $medicine->update($request->all());
        return redirect()->route('admin.medicine.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Medicine $medicine
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Medicine $medicine)
    {
        $medicine->delete();
        return redirect()->route('admin.medicine.index');
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function restore(int $id)
    {
        Medicine::withTrashed()->where('id', $id)->restore();
        return redirect()->route('admin.medicine.index');
    }
}
