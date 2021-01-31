@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1 class="text-primary pb-3">Restablecer contraseña</h1>
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div class="form-group col-md-6 p-0">
                        <label for="password">Contraseña *</label>
                        <input id="password" name="password" type="password" required
                               placeholder="Ingresa la contraseña" autofocus
                               class="form-control @error('password') is-invalid @enderror"
                               aria-describedby="validation-password">
                        @error('password')
                        <div id="validation-password" class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-6 p-0">
                        <label for="password_confirmation">Repetir contraseña *</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                               placeholder="Ingresa nuevamente la contraseña" class="form-control">
                    </div>

                    <div class="pt-3">
                        <button type="submit" class="btn btn-primary">
                            <span>Restablecer contraseña</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
