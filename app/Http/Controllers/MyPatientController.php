<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Patient;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class MyPatientController extends Controller
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
     * @return View
     */
    public function index(Request $request)
    {
        $searchValue = $request->get('search');
        if ($request->has('search') && $searchValue !== null) {
            $patients = Patient::search($searchValue)->whereHas('users', function ($q) {
                $q->where('users.id', Auth::id());
            })->paginate(10)->withQueryString();
        } else {
            $patients = Patient::withTrashed()->whereHas('users', function ($q) {
                $q->where('users.id', Auth::id());
            })->orderBy('name')->paginate(10);
        }
        return view('client.myPatient.index', compact('patients', 'searchValue'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        $cities = City::citiesAndProvinces();
        return view('client.myPatient.create', compact('cities'));
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
            'name' => 'bail|required|min:5|max:100|alpha_spaces|string',
            'last_name' => 'bail|required|min:5|max:100|alpha_spaces|string',
            'identification' => 'bail|required|digits:10|unique:patients|numeric',
            'email' => 'bail|nullable|max:255|email|string',
            'phone' => 'bail|nullable|digits:10|numeric',
            'address' => 'bail|required|min:5|max:200|string',
            'birthday' => 'bail|required|after:"1900-01-01"|before:today|date',
            'gender' => 'bail|required|in:M,F',
            'city_id' => 'bail|required',
        ]);
        $city = City::find($request->city_id);
        $patient = new Patient();
        $patient->fill($request->all());
        $patient->city()->associate($city);
        $patient->save();
        $patient->users()->attach([Auth::id()]);
        return redirect()->route('client.myPatient.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return View|RedirectResponse
     */
    public function show(int $id)
    {
        $patient = Patient::find($id);
        if (!$patient->isActive(Auth::id())) {
            return redirect()->route('client.myPatient.index');
        }

        return view('client.myPatient.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return View|RedirectResponse
     */
    public function edit(int $id)
    {
        $patient = Patient::find($id);
        if (!$patient->isActive(Auth::id())) {
            return redirect()->route('client.myPatient.index');
        }

        $cities = City::citiesAndProvinces();
        return view('client.myPatient.edit', compact('patient', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, int $id)
    {
        $patient = Patient::find($id);
        if (!$patient->isActive(Auth::id())) {
            return redirect()->route('client.myPatient.index');
        }

        $request->validate([
            'name' => 'bail|required|min:5|max:100|alpha_spaces|string',
            'last_name' => 'bail|required|min:5|max:100|alpha_spaces|string',
            'identification' => "bail|required|digits:10|unique:patients,identification,$patient->id|numeric",
            'email' => 'bail|nullable|max:255|email|string',
            'phone' => 'bail|nullable|digits:10|numeric',
            'address' => 'bail|required|min:5|max:200|string',
            'birthday' => 'bail|required|after:"1900-01-01"|before:today|date',
            'gender' => 'bail|required|in:M,F',
            'city_id' => 'bail|required',
        ]);
        $city = City::find($request->city_id);
        $patient->fill($request->all());
        $patient->city()->associate($city);
        $patient->save();
        return redirect()->route('client.myPatient.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id)
    {
        $patient = Patient::find($id);
        if ($patient->isActive(Auth::id())) {
            $patient->users()->updateExistingPivot(Auth::id(), ['deleted_at' => Date::now()]);
        }
        return redirect()->route('client.myPatient.index');
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function restore(int $id)
    {
        $patient = Patient::find($id);
        if (!$patient->isActive(Auth::id())) {
            $patient->users()->updateExistingPivot(Auth::id(), ['deleted_at' => null]);
        }
        return redirect()->route('client.myPatient.index');
    }
}
