@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-primary pb-3">Datos del cliente</h1>

        <div class="form-group col-md-6 p-0">
            <label for="name">Nombre</label>
            <input id="name" type="text" disabled value="{{ $client->name }}" class="form-control">
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="name">Apellidos</label>
            <input id="name" type="text" disabled value="{{ $client->last_name }}" class="form-control">
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="name">Cédula</label>
            <input id="name" type="text" disabled value="{{ $client->identification }}" class="form-control">
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="name">Email</label>
            <input id="name" type="text" disabled value="{{ $client->email }}" class="form-control">
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="name">Teléfono</label>
            <input id="name" type="text" disabled value="{{ $client->phone }}" class="form-control">
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="name">Dirección</label>
            <input id="name" type="text" disabled value="{{ $client->address }}" class="form-control">
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="name">Fecha de nacimiento</label>
            <input id="name" type="text" disabled value="{{ $client->birthday }}" class="form-control">
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="name">Género</label>
            <input id="name" type="text" disabled
                   value="{{ $client->gender === 'M' ? 'Masculino' : 'Femenino'  }}" class="form-control">
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="name">Ciudad</label>
            <input id="name" type="text" disabled value="{{ $client->city->name }}" class="form-control">
        </div>

        <div class="pt-3">
            <a href="{{ route('admin.client.index') }}" class="btn btn-primary">
                <span>Regresar</span>
            </a>
        </div>
    </div>
@endsection
