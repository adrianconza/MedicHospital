@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1 class="text-primary pb-3">Recuperar contraseña</h1>
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="form-group col-md-6 p-0">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="text" required autocomplete="email"
                               placeholder="Ingresa el email" autofocus value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror"
                               aria-describedby="validation-email">
                        @error('email')
                        <div id="validation-email" class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="pt-3">
                        <button type="submit" class="btn btn-primary">
                            <span>Enviar enlace</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
