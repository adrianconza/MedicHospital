@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-primary pb-3">Añadir un examen de imagen</h1>
        <form action="{{ route('admin.imagingExam.store') }}" method="POST" class="form">
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

            <div class="pt-3">
                <a href="{{ route('admin.imagingExam.index') }}" class="btn btn-secondary">
                    <span>Cancelar</span>
                </a>
                <button type="submit" class="btn btn-primary">
                    <span>Guardar</span>
                </button>
            </div>
        </form>
    </div>
@endsection
