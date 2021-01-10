<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    const ADMINISTRATOR = 'Administrator';
    const DOCTOR = 'Doctor';
    const CLIENT = 'Client';

    /**
     * Get the Administrator role.
     */
    public static function administrator()
    {
        return Role::where('name', Role::ADMINISTRATOR)->first();
    }

    /**
     * Get the Doctor role.
     */
    public static function doctor()
    {
        return Role::where('name', Role::DOCTOR)->first();
    }

    /**
     * Get the Client role.
     */
    public static function client()
    {
        return Role::where('name', Role::CLIENT)->first();
    }

    /**
     * Get the users for the role.
     */
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
