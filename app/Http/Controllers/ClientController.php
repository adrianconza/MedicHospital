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

class ClientController extends Controller
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
                $q->where('name', Role::CLIENT);
            })->paginate(10)->withQueryString();
        } else {
            $users = User::withTrashed()->whereHas('roles', function ($q) {
                $q->where('name', Role::CLIENT);
            })->orderBy('name')->paginate(10);
        }
        return view('admin.client.index', compact('users', 'searchValue'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        $cities = City::citiesAndProvinces();
        return view('admin.client.create', compact('cities'));
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
        $role = Role::client();
        $client = new User();
        $client->fill($request->all());
        $client->password = Hash::make($request->password);
        $client->city()->associate($city);
        $client->save();
        $client->roles()->attach([$role->id]);
        return redirect()->route('admin.client.index');
    }

    /**l
     * Display the specified resource.
     *
     * @param User $client
     * @return View|RedirectResponse
     */
    public function show(User $client)
    {
        if (!$client->isActiveClient()) {
            return redirect()->route('admin.client.index');
        }

        return view('admin.client.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $client
     * @return View|RedirectResponse
     */
    public function edit(User $client)
    {
        if (!$client->isActiveClient()) {
            return redirect()->route('admin.client.index');
        }

        $cities = City::citiesAndProvinces();
        return view('admin.client.edit', compact('client', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $client
     * @return RedirectResponse
     */
    public function update(Request $request, User $client)
    {
        if (!$client->isActiveClient()) {
            return redirect()->route('admin.client.index');
        }

        $request->validate([
            'email' => "bail|required|max:255|unique:users,email,$client->id|email|string",
            'identification' => "bail|required|digits:10|unique:users,identification,$client->id|numeric",
            'name' => 'bail|required|min:5|max:100|alpha_spaces|string',
            'last_name' => 'bail|required|min:5|max:100|alpha_spaces|string',
            'phone' => 'bail|required|digits:10|numeric',
            'address' => 'bail|nullable|min:5|max:200|string',
            'birthday' => 'bail|nullable|after:"1900-01-01"|before:today|date',
            'gender' => 'bail|nullable|in:M,F',
            'city_id' => 'bail|required',
        ]);
        $city = City::find($request->city_id);
        $client->fill($request->all());
        $client->city()->associate($city);
        $client->save();
        return redirect()->route('admin.client.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $client
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(User $client)
    {
        if (!$client->isActiveClient()) {
            return redirect()->route('admin.client.index');
        }

        $role = Role::client();
        $client->roles()->updateExistingPivot($role->id, ['deleted_at' => Date::now()]);
        return redirect()->route('admin.client.index');
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function restore(int $id)
    {
        $client = User::find($id);
        if (!$client->isDoctor() || $client->isActiveDoctor()) {
            return redirect()->route('admin.client.index');
        }

        $role = Role::doctor();
        $client->roles()->updateExistingPivot($role->id, ['deleted_at' => null]);
        return redirect()->route('admin.client.index');
    }
}
