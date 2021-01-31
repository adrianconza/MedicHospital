@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-primary pb-3">Editar una medicina</h1>
        <form action="{{ route('admin.medicine.update', $medicine) }}" method="POST" class="form">
            @method('PUT')
            @csrf

            <div class="form-group col-md-6 p-0">
                <label for="name">Nombre *</label>
                <input id="name" name="name" type="text" required placeholder="Ingresa el nombre" autofocus
                       value="{{ old('name', $medicine->name) }}"
                       class="form-control @error('name') is-invalid @enderror" aria-describedby="validation-name">
                @error('name')
                <div id="validation-name" class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="pt-3">
                <a href="{{ route('admin.medicine.index') }}" class="btn btn-secondary">
                    <span>Cancelar</span>
                </a>
                <button type="submit" class="btn btn-primary">
                    <span>Guardar</span>
                </button>
            </div>
        </form>
    </div>
@endsection
