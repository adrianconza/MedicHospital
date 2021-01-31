<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\AttentionSchedule;
use App\Models\City;
use App\Models\ImagingExam;
use App\Models\LaboratoryExam;
use App\Models\MedicalExam;
use App\Models\MedicalRecord;
use App\Models\MedicalSpeciality;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\Province;
use App\Models\Recipe;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        MedicalSpeciality::factory(50)->create();
        LaboratoryExam::factory(50)->create();
        ImagingExam::factory(50)->create();
        Medicine::factory(50)->create();

        Province::factory(10)->create()->each(function ($province) {
            $province->cities()->saveMany(City::factory(5)->make());
        });

        $cities = City::all();

        User::factory(50)->make()->each(function ($user) use ($cities) {
            $user->city()->associate($cities->random(1)->first());
            $user->save();
        });
        $this->call(RoleSeeder::class);
        $roles = Role::all();
        User::all()->each(function ($user) use ($roles) {
            $user->roles()->attach($roles->random(rand(1, 3))->pluck('id')->toArray());
        });

        Patient::factory(50)->make()->each(function ($patient) use ($cities) {
            $patient->city()->associate($cities->random(1)->first());
            $patient->save();
        });
        $patients = Patient::all();
        User::whereHas('roles', function ($q) {
            $q->where('name', 'Client');
        })->get()->each(function ($client) use ($patients) {
            $client->patients()->attach($patients->random(rand(1, 5))->pluck('id')->toArray());
        });

        $medicalSpecialities = MedicalSpeciality::all();
        $doctors = User::whereHas('roles', function ($q) {
            $q->where('name', 'Doctor');
        })->get();
        $doctors->each(function ($doctor) use ($medicalSpecialities) {
            $doctor->medicalSpecialities()->attach($medicalSpecialities->random(rand(1, 5))->pluck('id')->toArray());
            $doctor->attentionSchedules()->saveMany([
                new AttentionSchedule(['start_time' => '09:00', 'end_time' => '12:00']),
                new AttentionSchedule(['start_time' => '16:00', 'end_time' => '18:00'])
            ]);
        });

        Appointment::factory(50)->make()->each(function ($appointment) use ($patients, $medicalSpecialities, $doctors) {
            $appointment->patient()->associate($patients->random(1)->first());
            $appointment->medicalSpeciality()->associate($medicalSpecialities->random(1)->first());
            $appointment->user()->associate($doctors->random(1)->first());
            $appointment->save();
        });

        $appointments = Appointment::all();
        MedicalRecord::factory(100)->make()->each(function ($medicalRecord) use ($appointments) {
            $medicalRecord->appointment()->associate($appointments->random(1)->first());
            $medicalRecord->save();
        });

        $medicalRecords = MedicalRecord::all();
        $medicines = Medicine::all();
        Recipe::factory(100)->make()->each(function ($recipe) use ($medicines, $medicalRecords) {
            $recipe->medicine()->associate($medicines->random(1)->first());
            $recipe->medicalRecord()->associate($medicalRecords->random(1)->first());
            $recipe->save();
        });

        $imagingExams = ImagingExam::all();
        $laboratoryExams = LaboratoryExam::all();
        MedicalExam::factory(100)->make()->each(function ($medicalExam) use ($imagingExams, $laboratoryExams, $medicalRecords) {
            $medicalExam->imagingExam()->associate($imagingExams->random(1)->first());
            $medicalExam->laboratoryExam()->associate($laboratoryExams->random(1)->first());
            $medicalExam->medicalRecord()->associate($medicalRecords->random(1)->first());
            $medicalExam->save();
        });
    }
}
