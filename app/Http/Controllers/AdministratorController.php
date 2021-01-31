<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;

class AdministratorController extends Controller
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
            $users = User::search($searchValue)->whereHas('roles', function ($q) {
                $q->where('name', Role::ADMINISTRATOR);
            })->paginate(10)->withQueryString();
        } else {
            $users = User::withTrashed()->whereHas('roles', function ($q) {
                $q->where('name', Role::ADMINISTRATOR);
            })->orderBy('name')->paginate(10);
        }
        return view('admin.administrator.index', compact('users', 'searchValue'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        $cities = City::citiesAndProvinces();
        return view('admin.administrator.create', compact('cities'));
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
        ]);
        $city = City::find($request->city_id);
        $role = Role::administrator();
        $administrator = new User();
        $administrator->fill($request->all());
        $administrator->password = Hash::make($request->password);
        $administrator->city()->associate($city);
        $administrator->save();
        $administrator->roles()->attach([$role->id]);
        return redirect()->route('admin.administrator.index');
    }

    /**l
     * Display the specified resource.
     *
     * @param User $administrator
     * @return View|RedirectResponse
     */
    public function show(User $administrator)
    {
        if (!$administrator->isActiveAdministrator()) {
            return redirect()->route('admin.administrator.index');
        }

        return view('admin.administrator.show', compact('administrator'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $administrator
     * @return View|RedirectResponse
     */
    public function edit(User $administrator)
    {
        if (!$administrator->isActiveAdministrator()) {
            return redirect()->route('admin.administrator.index');
        }

        $cities = City::citiesAndProvinces();
        return view('admin.administrator.edit', compact('administrator', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $administrator
     * @return RedirectResponse
     */
    public function update(Request $request, User $administrator)
    {
        if (!$administrator->isActiveAdministrator()) {
            return redirect()->route('admin.administrator.index');
        }

        $request->validate([
            'email' => "bail|required|max:255|unique:users,email,$administrator->id|email|string",
            'identification' => "bail|required|digits:10|unique:users,identification,$administrator->id|numeric",
            'name' => 'bail|required|min:5|max:100|alpha_spaces|string',
            'last_name' => 'bail|required|min:5|max:100|alpha_spaces|string',
            'phone' => 'bail|required|digits:10|numeric',
            'address' => 'bail|nullable|min:5|max:200|string',
            'birthday' => 'bail|nullable|after:"1900-01-01"|before:today|date',
            'gender' => 'bail|nullable|in:M,F',
            'city_id' => 'bail|required',
        ]);
        $city = City::find($request->city_id);
        $administrator->fill($request->all());
        $administrator->city()->associate($city);
        $administrator->save();
        return redirect()->route('admin.administrator.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $administrator
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(User $administrator)
    {
        if (!$administrator->isActiveAdministrator()) {
            return redirect()->route('admin.administrator.index');
        }

        $role = Role::doctor();
        $administrator->roles()->updateExistingPivot($role->id, ['deleted_at' => Date::now()]);
        return redirect()->route('admin.administrator.index');
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function restore(int $id)
    {
        $administrator = User::find($id);;

        if (!$administrator->isAdministrator() || $administrator->isActiveAdministrator()) {
            return redirect()->route('admin.administrator.index');
        }

        $role = Role::doctor();
        $administrator->roles()->updateExistingPivot($role->id, ['deleted_at' => null]);
        return redirect()->route('admin.administrator.index');
    }
}
