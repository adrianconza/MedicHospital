<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Role;
use App\Models\Patient;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
//        dd(Auth::user());
        $searchValue = $request->get('search');
        if ($request->has('search') && $searchValue !== null) {
//            $patients = Patient::search($searchValue)->whereHas('roles', function ($q) {
//                $q->where('name', $this->role);
//            })->paginate(10)->withQueryString();
            $patients = Patient::search($searchValue)->paginate(10)->withQueryString();
        } else {
//            $patients = Patient::withTrashed()->whereHas('roles', function ($q) {
//                $q->where('name', $this->role);
//            })->orderBy('name')->paginate(10);
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
        $role = Role::where('name', $this->role)->first();
        $patient = new Patient();
        $patient->fill($request->all());
        $patient->city()->associate($city);
        $patient->save();
        $patient->roles()->attach([$role->id]);
        return redirect()->route('admin.patient.index');
    }

    /**l
     * Display the specified resource.
     *
     * @param int $id
     * @return View|RedirectResponse
     */
    public function show(int $id)
    {
        $patient = Patient::withTrashed()->whereHas('roles', function ($q) {
            $q->where('name', $this->role);
        })->where('id', $id)->first();

        if (!$patient) {
            return redirect()->route('admin.patient.index');
        }

        return view('admin.patient.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return View|RedirectResponse
     */
    public function edit(int $id)
    {
        $patient = Patient::withTrashed()->whereHas('roles', function ($q) {
            $q->where('name', $this->role);
        })->where('id', $id)->first();

        if (!$patient) {
            return redirect()->route('admin.patient.index');
        }

        $cities = City::query()->join('provinces', 'provinces.id', '=', 'cities.province_id')
            ->orderBy('provinces.name')->orderBy('cities.name')->select('cities.*')->get();
        return view('admin.patient.edit', compact('patient', 'cities'));
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
        $patient = Patient::withTrashed()->whereHas('roles', function ($q) {
            $q->where('name', $this->role);
        })->where('id', $id)->first();

        if (!$patient) {
            return redirect()->route('admin.patient.index');
        }

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
        $patient = new Patient();
        $patient->fill($request->all());
        $patient->city()->associate($city);
        $patient->save();
        return redirect()->route('admin.patient.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(int $id)
    {
        $patient = Patient::withTrashed()->whereHas('roles', function ($q) {
            $q->where('name', $this->role);
        })->where('id', $id)->first();

        if (!$patient) {
            return redirect()->route('admin.patient.index');
        }

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
        $patient = Patient::withTrashed()->whereHas('roles', function ($q) {
            $q->where('name', $this->role);
        })->where('id', $id)->first();

        if (!$patient) {
            return redirect()->route('admin.patient.index');
        }

        $patient->restore();
        return redirect()->route('admin.patient.index');
    }
}
