<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        $cities = City::citiesAndProvinces();
        return view('auth.register', compact('cities'));
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
        return redirect()->route('login');
    }
}
