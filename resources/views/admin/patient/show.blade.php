@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-primary pb-3">Datos del paciente</h1>

        <div class="form-group col-md-6 p-0">
            <label for="name">Nombre</label>
            <input id="name" type="text" disabled value="{{ $patient->name }}" class="form-control">
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="name">Apellidos</label>
            <input id="name" type="text" disabled value="{{ $patient->last_name }}" class="form-control">
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="name">Cédula</label>
            <input id="name" type="text" disabled value="{{ $patient->identification }}" class="form-control">
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="name">Email</label>
            <input id="name" type="text" disabled value="{{ $patient->email }}" class="form-control">
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="name">Teléfono</label>
            <input id="name" type="text" disabled value="{{ $patient->phone }}" class="form-control">
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="name">Dirección</label>
            <input id="name" type="text" disabled value="{{ $patient->address }}" class="form-control">
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="name">Fecha de nacimiento</label>
            <input id="name" type="text" disabled value="{{ $patient->birthday }}" class="form-control">
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="name">Género</label>
            <input id="name" type="text" disabled
                   value="{{ $patient->gender === 'M' ? 'Masculino' : 'Femenino'  }}" class="form-control">
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="name">Ciudad</label>
            <input id="name" type="text" disabled value="{{ $patient->city->name }}" class="form-control">
        </div>

        <div class="pt-3">
            <a href="{{ route('admin.patient.index') }}" class="btn btn-primary">
                <span>Regresar</span>
            </a>
        </div>
    </div>
@endsection
