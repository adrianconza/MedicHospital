<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    private $role = 'Médico';

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
                $q->where('name', $this->role);
            })->paginate(10)->withQueryString();
        } else {
            $users = User::withTrashed()->whereHas('roles', function ($q) {
                $q->where('name', $this->role);
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
        $cities = City::query()->join('provinces', 'provinces.id', '=', 'cities.province_id')
            ->orderBy('provinces.name')->orderBy('cities.name')->select('cities.*')->get();
        return view('admin.doctor.create', compact('cities'));
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
            'name' => 'bail|required|min:5|max:100|alpha|string',
            'last_name' => 'bail|required|min:5|max:100|alpha|string',
            'phone' => 'bail|required|digits:10|numeric',
            'address' => 'bail|nullable|min:5|max:200|alpha|string',
            'birthday' => 'bail|nullable|after:"1900-01-01"|before:today|date',
            'gender' => 'bail|nullable|in:M,F',
            'city_id' => 'bail|required',
        ]);
        $city = City::where('id', $request->city_id)->first();
        $role = Role::where('name', $this->role)->first();
        $doctor = new User();
        $doctor->fill($request->all());
        $doctor->city()->associate($city);
        $doctor->save();
        $doctor->roles()->attach([$role->id]);
        return redirect()->route('admin.doctor.index');
    }

    /**l
     * Display the specified resource.
     *
     * @param int $id
     * @return View|RedirectResponse
     */
    public function show(int $id)
    {
        $doctor = User::withTrashed()->whereHas('roles', function ($q) {
            $q->where('name', $this->role);
        })->where('id', $id)->first();

        if (!$doctor) {
            return redirect()->route('admin.doctor.index');
        }

        return view('admin.doctor.show', compact('doctor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return View|RedirectResponse
     */
    public function edit(int $id)
    {
        $doctor = User::withTrashed()->whereHas('roles', function ($q) {
            $q->where('name', $this->role);
        })->where('id', $id)->first();

        if (!$doctor) {
            return redirect()->route('admin.doctor.index');
        }

        $cities = City::query()->join('provinces', 'provinces.id', '=', 'cities.province_id')
            ->orderBy('provinces.name')->orderBy('cities.name')->select('cities.*')->get();
        return view('admin.doctor.edit', compact('doctor', 'cities'));
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
        $doctor = User::withTrashed()->whereHas('roles', function ($q) {
            $q->where('name', $this->role);
        })->where('id', $id)->first();

        if (!$doctor) {
            return redirect()->route('admin.doctor.index');
        }

        $request->validate([
            'email' => "bail|required|max:255|unique:users,email,$doctor->id|email|string",
            'password' => 'bail|required|min:8|confirmed|string',
            'identification' => "bail|required|digits:10|unique:users,identification,$doctor->id|numeric",
            'name' => 'bail|required|min:5|max:100|alpha|string',
            'last_name' => 'bail|required|min:5|max:100|alpha|string',
            'phone' => 'bail|required|digits:10|numeric',
            'address' => 'bail|nullable|min:5|max:200|alpha|string',
            'birthday' => 'bail|nullable|after:"1900-01-01"|before:today|date',
            'gender' => 'bail|nullable|in:M,F',
            'city_id' => 'bail|required',
        ]);
        $city = City::where('id', $request->city_id)->first();
        $doctor = new User();
        $doctor->fill($request->all());
        $doctor->city()->associate($city);
        $doctor->save();
        return redirect()->route('admin.doctor.index');
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
        $doctor = User::withTrashed()->whereHas('roles', function ($q) {
            $q->where('name', $this->role);
        })->where('id', $id)->first();

        if (!$doctor) {
            return redirect()->route('admin.doctor.index');
        }

        $doctor->delete();
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
        $doctor = User::withTrashed()->whereHas('roles', function ($q) {
            $q->where('name', $this->role);
        })->where('id', $id)->first();

        if (!$doctor) {
            return redirect()->route('admin.doctor.index');
        }

        $doctor->restore();
        return redirect()->route('admin.doctor.index');
    }
}
