@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-primary pb-3">Añadir mi paciente</h1>
        <form action="{{ route('client.myPatient.store') }}" method="POST" class="form">
            @csrf

            <div class="form-group col-md-6 p-0">
                <label for="name">Nombre *</label>
                <input id="name" name="name" type="text" required placeholder="Ingresa el nombre" autofocus
                       value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror"
                       aria-describedby="validation-name">
                @error('name')
                <div id="validation-name" class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group col-md-6 p-0">
                <label for="last_name">Apellido *</label>
                <input id="last_name" name="last_name" type="text" required placeholder="Ingresa el apellido"
                       value="{{ old('last_name') }}" class="form-control @error('last_name') is-invalid @enderror"
                       aria-describedby="validation-last-name">
                @error('last_name')
                <div id="validation-last-name" class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group col-md-6 p-0">
                <label for="identification">Cédula *</label>
                <input id="identification" name="identification" type="text" required placeholder="Ingresa la cédula"
                       value="{{ old('identification') }}"
                       class="form-control @error('identification') is-invalid @enderror"
                       aria-describedby="validation-identification">
                @error('identification')
                <div id="validation-identification" class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group col-md-6 p-0">
                <label for="email">Email</label>
                <input id="email" name="email" type="text" required placeholder="Ingresa el email"
                       value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror"
                       aria-describedby="validation-email">
                @error('email')
                <div id="validation-email" class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group col-md-6 p-0">
                <label for="phone">Teléfono</label>
                <input id="phone" name="phone" type="text" required placeholder="Ingresa el teléfono"
                       value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror"
                       aria-describedby="validation-phone">
                @error('phone')
                <div id="validation-phone" class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group col-md-6 p-0">
                <label for="address">Dirección *</label>
                <input id="address" name="address" type="text" placeholder="Ingresa la dirección"
                       value="{{ old('address') }}" class="form-control @error('address') is-invalid @enderror"
                       aria-describedby="validation-address">
                @error('address')
                <div id="validation-address" class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group col-md-6 p-0">
                <label for="birthday">Fecha de nacimiento *</label>
                <input id="birthday" name="birthday" type="text" placeholder="Ingresa la fecha de nacimiento"
                       value="{{ old('birthday') }}" class="form-control @error('birthday') is-invalid @enderror"
                       onfocus="(this.type='date')"
                       aria-describedby="validation-birthday">
                @error('birthday')
                <div id="validation-birthday" class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group col-md-6 p-0">
                <label for="gender">Género *</label>
                <select id="gender" name="gender" class="form-control @error('gender') is-invalid @enderror"
                        aria-describedby="validation-gender">
                    <option value="{{ null }}">Selecciona el género</option>
                    <option value="M" {{ old('gender') === 'M' ? 'selected' : '' }}>Masculino</option>
                    <option value="F" {{ old('gender') === 'F' ? 'selected' : '' }}>Femenino</option>
                </select>
                @error('gender')
                <div id="validation-gender" class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group col-md-6 p-0">
                <label for="city_id">Ciudad *</label>
                <select id="city_id" name="city_id" class="form-control @error('city_id') is-invalid @enderror"
                        aria-describedby="validation-city">
                    <option value="">Selecciona la ciudad</option>
                    @foreach($cities as $city)
                        <option
                            value="{{ $city->id }}" {{ +old('city_id') === +$city->id ? 'selected' : '' }}>{{ $city->province->name }}
                            , {{ $city->name }}</option>
                    @endforeach
                </select>
                @error('city_id')
                <div id="validation-city" class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="pt-3">
                <a href="{{ route('client.myPatient.index') }}" class="btn btn-secondary">
                    <span>Cancelar</span>
                </a>
                <button type="submit" class="btn btn-primary">
                    <span>Guardar</span>
                </button>
            </div>
        </form>
    </div>
@endsection
