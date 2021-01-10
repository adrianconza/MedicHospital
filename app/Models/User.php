<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Nicolaslopezj\Searchable\SearchableTrait;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, SearchableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'identification',
        'name',
        'last_name',
        'phone',
        'address',
        'birthday',
        'gender',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'users.email' => 10,
            'users.identification' => 10,
            'users.name' => 10,
            'users.last_name' => 10,
        ]
    ];


    /**
     * Get the city for the user.
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the roles for the user.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    /**
     * Have rol administrator for the user.
     *
     * @return bool
     */
    public function isAdministrator()
    {
        return $this->haveRol(Role::administrator());
    }

    /**
     * Get the active rol administrator for the user.
     *
     * @return bool
     */
    public function isActiveAdministrator()
    {
        return $this->isActiveRol(Role::administrator());
    }

    /**
     * Have rol doctor for the user.
     *
     * @return bool
     */
    public function isDoctor()
    {
        return $this->haveRol(Role::doctor());
    }

    /**
     * Get the active rol doctor for the user.
     *
     * @return bool
     */
    public function isActiveDoctor()
    {
        return $this->isActiveRol(Role::doctor());
    }

    /**
     * Have rol client for the user.
     *
     * @return bool
     */
    public function isClient()
    {
        return $this->haveRol(Role::doctor());
    }

    /**
     * Get the active rol client for the user.
     *
     * @return bool
     */
    public function isActiveClient()
    {
        return $this->isActiveRol(Role::client());
    }

    /**
     * Have rol for the user.
     *
     * @param Role $role
     * @return bool
     */
    private function haveRol(Role $role)
    {
        $haveRole = $this->roles()->wherePivot('role_id', $role->id)->first();
        return !!$haveRole;
    }

    /**
     * Get the active rol for the user.
     *
     * @param Role $role
     * @return bool
     */
    private function isActiveRol(Role $role)
    {
        $validRole = $this->roles()->wherePivot('role_id', $role->id)->withPivot('deleted_at')->first();
        return $validRole ? $validRole->pivot->deleted_at === null : false;
    }

    /**
     * Get the patients for the user.
     */
    public function patients()
    {
        return $this->belongsToMany(Patient::class)->withTimestamps();
    }

    /**
     * Get the medical specialities for the user.
     */
    public function medicalSpecialities()
    {
        return $this->belongsToMany(MedicalSpeciality::class)->withTimestamps();
    }

    /**
     * Get the attention schedules for the user.
     */
    public function attentionSchedules()
    {
        return $this->hasMany(AttentionSchedule::class);
    }
}
