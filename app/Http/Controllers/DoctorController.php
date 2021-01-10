<?php

namespace App\Http\Controllers;

use App\Models\AttentionSchedule;
use App\Models\City;
use App\Models\MedicalSpeciality;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
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
            $users = User::search($searchValue)->whereHas('roles', function ($q) {
                $q->where('name', Role::DOCTOR);
            })->paginate(10)->withQueryString();
        } else {
            $users = User::withTrashed()->whereHas('roles', function ($q) {
                $q->where('name', Role::DOCTOR);
            })->orderBy('name')->paginate(10);
        }
        return view('admin.doctor.index', compact('users', 'searchValue'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        $cities = City::citiesAndProvinces();
        $medicalSpecialities = MedicalSpeciality::orderBy('name')->get();
        return view('admin.doctor.create', compact('cities', 'medicalSpecialities'));
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
            'email' => 'bail|required|max:255|unique:users|email|string',
            'password' => 'bail|required|min:8|confirmed|string',
            'identification' => 'bail|required|digits:10|unique:users|numeric',
            'name' => 'bail|required|min:5|max:100|alpha_spaces|string',
            'last_name' => 'bail|required|min:5|max:100|alpha_spaces|string',
            'phone' => 'bail|required|digits:10|numeric',
            'address' => 'bail|nullable|min:5|max:200|string',
            'birthday' => 'bail|nullable|after:"1900-01-01"|before:today|date',
            'gender' => 'bail|nullable|in:M,F',
            'city_id' => 'bail|required',
            'medical_specialities' => 'bail|required|present|array',
        ]);
        $city = City::find($request->city_id);
        $role = Role::doctor();
        $doctor = new User();
        $doctor->fill($request->all());
        $doctor->password = Hash::make($request->password);
        $doctor->city()->associate($city);
        $doctor->save();
        $doctor->roles()->attach([$role->id]);
        $doctor->medicalSpecialities()->attach($request->medical_specialities);
        $doctor->attentionSchedules()->saveMany([
            new AttentionSchedule(['start_time' => '09:00', 'end_time' => '12:00']),
            new AttentionSchedule(['start_time' => '16:00', 'end_time' => '18:00'])
        ]);
        return redirect()->route('admin.doctor.index');
    }

    /**l
     * Display the specified resource.
     *
     * @param User $doctor
     * @return View|RedirectResponse
     */
    public function show(User $doctor)
    {
        if (!$doctor->isActiveDoctor()) {
            return redirect()->route('admin.doctor.index');
        }

        return view('admin.doctor.show', compact('doctor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $doctor
     * @return View|RedirectResponse
     */
    public function edit(User $doctor)
    {
        if (!$doctor->isActiveDoctor()) {
            return redirect()->route('admin.doctor.index');
        }

        $cities = City::citiesAndProvinces();
        $medicalSpecialities = MedicalSpeciality::orderBy('name')->get();
        return view('admin.doctor.edit', compact('doctor', 'cities', 'medicalSpecialities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $doctor
     * @return RedirectResponse
     */
    public function update(Request $request, User $doctor)
    {
        if (!$doctor->isActiveDoctor()) {
            return redirect()->route('admin.doctor.index');
        }

        $request->validate([
            'email' => "bail|required|max:255|unique:users,email,$doctor->id|email|string",
            'identification' => "bail|required|digits:10|unique:users,identification,$doctor->id|numeric",
            'name' => 'bail|required|min:5|max:100|alpha_spaces|string',
            'last_name' => 'bail|required|min:5|max:100|alpha_spaces|string',
            'phone' => 'bail|required|digits:10|numeric',
            'address' => 'bail|nullable|min:5|max:200|string',
            'birthday' => 'bail|nullable|after:"1900-01-01"|before:today|date',
            'gender' => 'bail|nullable|in:M,F',
            'city_id' => 'bail|required',
            'medical_specialities' => 'bail|required|present|array',
        ]);
        $city = City::find($request->city_id);
        $doctor->fill($request->all());
        $doctor->city()->associate($city);
        $doctor->save();
        $doctor->medicalSpecialities()->sync($request->medical_specialities);
        return redirect()->route('admin.doctor.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $doctor
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(User $doctor)
    {
        if (!$doctor->isActiveDoctor()) {
            return redirect()->route('admin.doctor.index');
        }

        $role = Role::doctor();
        $doctor->roles()->updateExistingPivot($role->id, ['deleted_at' => Date::now()]);
        return redirect()->route('admin.doctor.index');
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function restore(int $id)
    {
        $doctor = User::find($id);
        if (!$doctor->isDoctor() || $doctor->isActiveDoctor()) {
            return redirect()->route('admin.doctor.index');
        }

        $role = Role::doctor();
        $doctor->roles()->updateExistingPivot($role->id, ['deleted_at' => null]);
        return redirect()->route('admin.doctor.index');
    }
}
