<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <nav class="navbar sticky-top navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">Registrarse</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarUser" class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                Hola, {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarUser">
                                @if (Auth::user()->isActiveAdministrator())
                                    @if (Auth::user()->isActiveDoctor() || Auth::user()->isActiveClient())
                                        <a class="dropdown-item disabled font-weight-bold text-primary" href="#">Administrador</a>
                                        <div class="dropdown-divider"></div>
                                    @endif
                                    <a class="dropdown-item" href="{{ route('admin.administrator.index') }}">
                                        Administradores
                                    </a>
                                    <a class="dropdown-item" href="{{ route('admin.doctor.index') }}">
                                        Doctores
                                    </a>
                                    <a class="dropdown-item" href="{{ route('admin.client.index') }}">
                                        Clientes
                                    </a>
                                    <a class="dropdown-item" href="{{ route('admin.patient.index') }}">
                                        Pacientes
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('admin.medicalSpeciality.index') }}">
                                        Especialidades médicas
                                    </a>
                                    <a class="dropdown-item" href="{{ route('admin.laboratoryExam.index') }}">
                                        Exámenes de laboratorio
                                    </a>
                                    <a class="dropdown-item" href="{{ route('admin.imagingExam.index') }}">
                                        Exámenes de imagen
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('admin.appointment.index') }}">
                                        Citas médicas
                                    </a>
                                @endif
                                @if (Auth::user()->isActiveDoctor())
                                    @if (Auth::user()->isActiveAdministrator() || Auth::user()->isActiveClient())
                                        @if (Auth::user()->isActiveAdministrator())
                                            <div class="dropdown-divider"></div>
                                        @endif
                                        <a class="dropdown-item disabled font-weight-bold text-primary"
                                           href="#">Doctor</a>
                                        <div class="dropdown-divider"></div>
                                    @endif
                                    <a class="dropdown-item" href="{{ route('admin.appointment.index') }}">
                                        Citas médicas 2
                                    </a>
                                @endif
                                @if (Auth::user()->isActiveClient())
                                    @if (Auth::user()->isActiveAdministrator() || Auth::user()->isActiveDoctor())
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item disabled font-weight-bold text-primary"
                                           href="#">Cliente</a>
                                        <div class="dropdown-divider"></div>
                                    @endif
                                    <a class="dropdown-item" href="{{ route('admin.appointment.index') }}">
                                        Citas médicas 3
                                    </a>
                                @endif
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    Salir
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>
</div>
</body>
</html>
