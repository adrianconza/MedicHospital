@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1 class="text-primary pb-3">Iniciar sesión</h1>
                <span class="text-secondary d-block pb-3">Accede a Medic Hospital</span>
                <form method="POST" action="{{ route('login') }}">
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

                    <div class="form-group col-md-6 p-0">
                        <label for="email">Contraseña</label>
                        <input id="password" name="password" type="password" required autocomplete="current-password"
                               placeholder="Ingresa la contraseña"
                               class="form-control @error('password') is-invalid @enderror"
                               aria-describedby="validation-password">
                        @error('password')
                        <div id="validation-epassword" class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-6 p-0">
                        <div class="form-check">
                            <input id="remember" name="remember" type="checkbox" class="form-check-input"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember" class="form-check-label">
                                Recuérdame
                            </label>
                        </div>
                    </div>

                    <div class="pt-3">
                        <button type="submit" class="btn btn-primary">
                            <span>Ingresar</span>
                        </button>
                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                Recuperar contraseña
                            </a>
                        @endif
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
