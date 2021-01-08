<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdministratorController extends Controller
{
    private $role = 'Administrador';

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
        return view('admin.administrator.index', compact('users', 'searchValue'));
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
        $administrator = new User();
        $administrator->fill($request->all());
        $administrator->city()->associate($city);
        $administrator->save();
        $administrator->roles()->attach([$role->id]);
        return redirect()->route('admin.administrator.index');
    }

    /**l
     * Display the specified resource.
     *
     * @param int $id
     * @return View|RedirectResponse
     */
    public function show(int $id)
    {
        $administrator = User::withTrashed()->whereHas('roles', function ($q) {
            $q->where('name', $this->role);
        })->where('id', $id)->first();

        if (!$administrator) {
            return redirect()->route('admin.administrator.index');
        }

        return view('admin.administrator.show', compact('administrator'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return View|RedirectResponse
     */
    public function edit(int $id)
    {
        $administrator = User::withTrashed()->whereHas('roles', function ($q) {
            $q->where('name', $this->role);
        })->where('id', $id)->first();

        if (!$administrator) {
            return redirect()->route('admin.administrator.index');
        }

        $cities = City::query()->join('provinces', 'provinces.id', '=', 'cities.province_id')
            ->orderBy('provinces.name')->orderBy('cities.name')->select('cities.*')->get();
        return view('admin.administrator.edit', compact('administrator', 'cities'));
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
        $administrator = User::withTrashed()->whereHas('roles', function ($q) {
            $q->where('name', $this->role);
        })->where('id', $id)->first();

        if (!$administrator) {
            return redirect()->route('admin.administrator.index');
        }

        $request->validate([
            'email' => "bail|required|max:255|unique:users,email,$administrator->id|email|string",
            'password' => 'bail|required|min:8|confirmed|string',
            'identification' => "bail|required|digits:10|unique:users,identification,$administrator->id|numeric",
            'name' => 'bail|required|min:5|max:100|alpha|string',
            'last_name' => 'bail|required|min:5|max:100|alpha|string',
            'phone' => 'bail|required|digits:10|numeric',
            'address' => 'bail|nullable|min:5|max:200|alpha|string',
            'birthday' => 'bail|nullable|after:"1900-01-01"|before:today|date',
            'gender' => 'bail|nullable|in:M,F',
            'city_id' => 'bail|required',
        ]);
        $city = City::where('id', $request->city_id)->first();
        $administrator = new User();
        $administrator->fill($request->all());
        $administrator->city()->associate($city);
        $administrator->save();
        return redirect()->route('admin.administrator.index');
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
        $administrator = User::withTrashed()->whereHas('roles', function ($q) {
            $q->where('name', $this->role);
        })->where('id', $id)->first();

        if (!$administrator) {
            return redirect()->route('admin.administrator.index');
        }

        $administrator->delete();
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
        $administrator = User::withTrashed()->whereHas('roles', function ($q) {
            $q->where('name', $this->role);
        })->where('id', $id)->first();

        if (!$administrator) {
            return redirect()->route('admin.administrator.index');
        }

        $administrator->restore();
        return redirect()->route('admin.administrator.index');
    }
}
