<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Patient;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
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
            $patients = Patient::search($searchValue)->paginate(10)->withQueryString();
        } else {
            $patients = Patient::withTrashed()->orderBy('name')->paginate(10);
        }
        return view('admin.patient.index', compact('patients', 'searchValue'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        $cities = City::query()->join('provinces', 'provinces.id', '=', 'cities.province_id')
            ->orderBy('provinces.name')->orderBy('cities.name')->select('cities.*')->get();
        return view('admin.patient.create', compact('cities'));
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
            'identification' => 'bail|required|digits:10|unique:patients|numeric',
            'name' => 'bail|required|min:5|max:100|alpha|string',
            'last_name' => 'bail|required|min:5|max:100|alpha|string',
            'email' => 'bail|nullable|max:255|email|string',
            'phone' => 'bail|nullable|digits:10|numeric',
            'address' => 'bail|required|min:5|max:200|alpha|string',
            'birthday' => 'bail|required|after:"1900-01-01"|before:today|date',
            'gender' => 'bail|required|in:M,F',
            'city_id' => 'bail|required',
        ]);
        $city = City::where('id', $request->city_id)->first();
        $patient = new Patient();
        $patient->fill($request->all());
        $patient->city()->associate($city);
        $patient->save();
        return redirect()->route('admin.patient.index');
    }

    /**l
     * Display the specified resource.
     *
     * @param Patient $patient
     * @return View|RedirectResponse
     */
    public function show(Patient $patient)
    {
        return view('admin.patient.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Patient $patient
     * @return View|RedirectResponse
     */
    public function edit(Patient $patient)
    {
        $cities = City::query()->join('provinces', 'provinces.id', '=', 'cities.province_id')
            ->orderBy('provinces.name')->orderBy('cities.name')->select('cities.*')->get();
        return view('admin.patient.edit', compact('patient', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Patient $patient
     * @return RedirectResponse
     */
    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'identification' => "bail|required|digits:10|unique:patients,identification,$patient->id|numeric",
            'name' => 'bail|required|min:5|max:100|alpha|string',
            'last_name' => 'bail|required|min:5|max:100|alpha|string',
            'email' => 'bail|nullable|max:255|email|string',
            'phone' => 'bail|nullable|digits:10|numeric',
            'address' => 'bail|required|min:5|max:200|alpha|string',
            'birthday' => 'bail|required|after:"1900-01-01"|before:today|date',
            'gender' => 'bail|required|in:M,F',
            'city_id' => 'bail|required',
        ]);
        $city = City::where('id', $request->city_id)->first();
        $patient->fill($request->all());
        $patient->city()->associate($city);
        $patient->save();
        return redirect()->route('admin.patient.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Patient $patient
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('admin.patient.index');
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function restore(int $id)
    {
        Patient::withTrashed()->where('id', $id)->restore();
        return redirect()->route('admin.patient.index');
    }
}
