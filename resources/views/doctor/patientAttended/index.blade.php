@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex flex-row justify-content-between align-items-center pb-4">
            <h1 class="text-primary">Historia médica</h1>
        </div>

        <form method="GET" action="{{ route('doctor.patientAttended.index') }}" class="p-0 pb-3 col-md-6">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span id="search-icon" class="input-group-text"><i class="fas fa-search"></i></span>
                </div>
                <input name="search" type="text" placeholder="Ingresa un texto para buscar..."
                       value="{{ $searchValue }}" class="form-control"
                       aria-label="Ingresa un texto para buscar..."
                       aria-describedby="search-icon search-icon">
                <div class="input-group-append">
                    <button id="search-icon" type="submit" class="btn btn-outline-primary">Buscar</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Nombre</th>
                    <th scope="col">Cédula</th>
                    <th scope="col">Email</th>
                    <th scope="col">Teléfono</th>
                    <th scope="col">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @if($patients->count())
                    @foreach($patients as $patient)
                        <tr id="patient-{{ $patient->id }}">
                            <td class="align-middle">{{ $patient->name }} {{ $patient->last_name }}</td>
                            <td class="align-middle">{{ $patient->identification }}</td>
                            <td class="align-middle">{{ $patient->email }}</td>
                            <td class="align-middle">{{ $patient->phone }}</td>
                            <td class="align-middle col-action">
                                <div class="d-flex flex-row justify-content-end align-items-center">
                                    <a href="{{ route('doctor.patientMedicalRecord.index', ['patient' => $patient->id]) }}"
                                       class="btn btn-primary mr-1">Ver</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center">No se encontraron resultados.</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
        <div class="d-flex flex-row justify-content-end">
            {{ $patients->links() }}
        </div>
    </div>
@endsection
