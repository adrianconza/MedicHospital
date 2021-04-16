<?php

use App\Http\Controllers\AdministratorController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImagingExamController;
use App\Http\Controllers\LaboratoryExamController;
use App\Http\Controllers\MedicalExamController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\MedicalSpecialityController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\MyAppointmentController;
use App\Http\Controllers\MyMedicalRecordController;
use App\Http\Controllers\MyPatientController;
use App\Http\Controllers\NextAppointmentController;
use App\Http\Controllers\PatientAttendedController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientMedicalRecordController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ReportAppointmentsQualificationsController;
use App\Http\Controllers\ScheduleController;
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

Route::redirect('/', '/login');

Auth::routes();

Route::get('/register', [RegisterController::class, 'create'])->name('auth.register');
Route::post('/register', [RegisterController::class, 'store'])->name('auth.register.store');

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::resource('administrator', AdministratorController::class)->names('admin.administrator');
Route::put('administrator/{id}/restore', [AdministratorController::class, 'restore'])->name('admin.administrator.restore');

Route::resource('doctor', DoctorController::class)->names('admin.doctor');
Route::put('doctor/{id}/restore', [DoctorController::class, 'restore'])->name('admin.doctor.restore');

Route::resource('client', ClientController::class)->names('admin.client');
Route::put('client/{id}/restore', [ClientController::class, 'restore'])->name('admin.client.restore');

Route::resource('patient', PatientController::class)->names('admin.patient');
Route::put('patient/{id}/restore', [PatientController::class, 'restore'])->name('admin.patient.restore');

Route::resource('medical-speciality', MedicalSpecialityController::class)->names('admin.medicalSpeciality');
Route::put('medical-speciality/{id}/restore', [MedicalSpecialityController::class, 'restore'])->name('admin.medicalSpeciality.restore');

Route::resource('laboratory-exam', LaboratoryExamController::class)->names('admin.laboratoryExam');
Route::put('laboratory-exam/{id}/restore', [LaboratoryExamController::class, 'restore'])->name('admin.laboratoryExam.restore');

Route::resource('imaging-exam', ImagingExamController::class)->names('admin.imagingExam');
Route::put('imaging-exam/{id}/restore', [ImagingExamController::class, 'restore'])->name('admin.imagingExam.restore');

Route::resource('medicine', MedicineController::class)->names('admin.medicine');
Route::put('medicine/{id}/restore', [MedicineController::class, 'restore'])->name('admin.medicine.restore');

Route::resource('appointment', AppointmentController::class)->except(['edit', 'update'])->names('admin.appointment');

Route::resource('medical-exam', MedicalExamController::class)->only(['index', 'edit', 'update'])->names('admin.medicalExam');

Route::resource('report-appointments-qualifications', ReportAppointmentsQualificationsController::class)->only(['index'])->names('admin.reportAppointmentsQualifications');

Route::resource('schedule', ScheduleController::class)->only(['index'])->names('doctor.schedule');

Route::resource('medical-record', MedicalRecordController::class)->except(['edit', 'update', 'destroy'])->names('doctor.medicalRecord');

Route::resource('next-appointment', NextAppointmentController::class)->only(['create', 'store'])->names('doctor.nextAppointment');

Route::resource('patient-attended', PatientAttendedController::class)->only(['index'])->names('doctor.patientAttended');

Route::resource('patient-medical-record', PatientMedicalRecordController::class)->only(['index', 'show'])->names('doctor.patientMedicalRecord');

Route::resource('my-patient', MyPatientController::class)->names('client.myPatient');
Route::put('my-patient/{id}/restore', [MyPatientController::class, 'restore'])->name('client.myPatient.restore');

Route::resource('my-appointment', MyAppointmentController::class)->except(['edit', 'update', 'destroy'])->names('client.myAppointment');

Route::resource('my-medical-record', MyMedicalRecordController::class)->only(['index', 'show', 'update'])->names('client.myMedicalRecord');
