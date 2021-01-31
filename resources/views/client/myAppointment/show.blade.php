@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-primary pb-3">Datos de la cita médica</h1>

        <div class="form-group col-md-6 p-0">
            <label for="start_time">Fecha inicio</label>
            <input id="start_time" type="text" disabled value="{{ $appointment->start_time }}" class="form-control">
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="end_time">Fecha fin</label>
            <input id="end_time" type="text" disabled value="{{ $appointment->end_time }}" class="form-control">
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="duration">Duración</label>
            <input id="duration" type="text" disabled value="{{ $appointment->duration }}" class="form-control">
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="patient">Paciente</label>
            <input id="patient" type="text" disabled
                   value="{{ $appointment->patient->name }} {{ $appointment->patient->last_name }}"
                   class="form-control">
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="doctor">Médico</label>
            <input id="doctor" type="text" disabled
                   value="{{ $appointment->user->name }} {{ $appointment->user->last_name }}" class="form-control">
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="medical-speciality">Especialidad</label>
            <input id="medical-speciality" type="text" disabled
                   value="{{ $appointment->medicalSpeciality->name }}"
                   class="form-control">
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="reason">Razón</label>
            <textarea id="reason" rows="5" disabled class="form-control">{{ $appointment->reason }}</textarea>
        </div>

        <div class="pt-3">
            <a href="{{ route('client.myAppointment.index') }}" class="btn btn-primary">
                <span>Regresar</span>
            </a>
        </div>
    </div>
@endsection
