<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $administrator = new Role();
        $administrator->name = 'Administrador';
        $administrator->save();

        $doctor = new Role();
        $doctor->name = 'Médico';
        $doctor->save();

        $client = new Role();
        $client->name = 'Cliente';
        $client->save();
    }
}
