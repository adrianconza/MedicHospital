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
        $administrator = new Role(['name' => Role::ADMINISTRATOR]);
        $administrator->save();

        $doctor = new Role(['name' => Role::DOCTOR]);
        $doctor->save();

        $client = new Role(['name' => Role::CLIENT]);
        $client->save();
    }
}
