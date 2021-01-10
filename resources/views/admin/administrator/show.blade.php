@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-primary pb-3">Datos del administrador</h1>

        <div class="form-group col-md-6 p-0">
            <label for="name">Nombre</label>
            <input id="name" type="text" disabled value="{{ $administrator->name }}" class="form-control">
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="name">Apellidos</label>
            <input id="name" type="text" disabled value="{{ $administrator->last_name }}" class="form-control">
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="name">Cédula</label>
            <input id="name" type="text" disabled value="{{ $administrator->identification }}" class="form-control">
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="name">Email</label>
            <input id="name" type="text" disabled value="{{ $administrator->email }}" class="form-control">
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="name">Teléfono</label>
            <input id="name" type="text" disabled value="{{ $administrator->phone }}" class="form-control">
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="name">Dirección</label>
            <input id="name" type="text" disabled value="{{ $administrator->address }}" class="form-control">
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="name">Fecha de nacimiento</label>
            <input id="name" type="text" disabled value="{{ $administrator->birthday }}" class="form-control">
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="name">Género</label>
            <input id="name" type="text" disabled
                   value="{{ $administrator->gender === 'M' ? 'Masculino' : 'Femenino'  }}" class="form-control">
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="name">Ciudad</label>
            <input id="name" type="text" disabled value="{{ $administrator->city->name }}" class="form-control">
        </div>

        <div class="pt-3">
            <a href="{{ route('admin.administrator.index') }}" class="btn btn-primary">
                <span>Regresar</span>
            </a>
        </div>
    </div>
@endsection
