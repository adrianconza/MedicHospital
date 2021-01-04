<?php

use App\Http\Controllers\AdministratorController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ImagingExamController;
use App\Http\Controllers\LaboratoryExamController;
use App\Http\Controllers\MedicalSpecialityController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('administrator', AdministratorController::class)->names('admin.administrator');
Route::put('administrator/{id}/restore', [AdministratorController::class, 'restore'])->name('admin.administrator.restore');

Route::resource('doctor', DoctorController::class)->names('admin.doctor');
Route::put('doctor/{id}/restore', [DoctorController::class, 'restore'])->name('admin.doctor.restore');

Route::resource('medical-speciality', MedicalSpecialityController::class)->names('admin.medicalSpeciality');
Route::put('medical-speciality/{id}/restore', [MedicalSpecialityController::class, 'restore'])->name('admin.medicalSpeciality.restore');

Route::resource('laboratory-exam', LaboratoryExamController::class)->names('admin.laboratoryExam');
Route::put('laboratory-exam/{id}/restore', [LaboratoryExamController::class, 'restore'])->name('admin.laboratoryExam.restore');

Route::resource('imaging-exam', ImagingExamController::class)->names('admin.imagingExam');
Route::put('imaging-exam/{id}/restore', [ImagingExamController::class, 'restore'])->name('admin.imagingExam.restore');
