@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex flex-row justify-content-between align-items-center pb-4">
            <h1 class="text-primary">Mis citas médicas</h1>
            <div>
                <a href="{{ route('client.myAppointment.create') }}" class="btn btn-primary px-4">
                    <span>Agendar</span>
                </a>
            </div>
        </div>

        <form method="GET" action="{{ route('client.myAppointment.index') }}" class="p-0 pb-5">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="patient_search">Paciente</label>
                    <select id="patient_search" name="patient_search" class="form-control">
                        <option value="">Selecciona un paciente</option>
                        @foreach($patients as $patient)
                            <option
                                value="{{ $patient->id }}" {{ +$patientSearch === +$patient->id ? 'selected' : '' }}>{{ $patient->name }} {{ $patient->last_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-outline-primary">Buscar</button>
            <a href="{{ route('client.myAppointment.index') }}" class="btn btn-outline-danger">
                <span>Limpiar filtros</span>
            </a>
        </form>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Fecha inicio</th>
                    <th scope="col">Fecha fin</th>
                    <th scope="col">Duración</th>
                    <th scope="col">Paciente</th>
                    <th scope="col">Médico</th>
                    <th scope="col">Especialidad</th>
                    <th scope="col">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @if($appointments->count())
                    @foreach($appointments as $appointment)
                        <tr id="appointment-{{ $appointment->id }}">
                            <td class="align-middle">{{ $appointment->start_time }}</td>
                            <td class="align-middle">{{ $appointment->end_time }}</td>
                            <td class="align-middle">{{ $appointment->duration }}</td>
                            <td class="align-middle">{{ $appointment->patient->name }} {{ $appointment->patient->last_name }}</td>
                            <td class="align-middle">{{ $appointment->user->name }} {{ $appointment->user->last_name }}</td>
                            <td class="align-middle">{{ $appointment->medicalSpeciality->name }}</td>
                            <td class="align-middle col-action">
                                <div class="d-flex flex-row justify-content-end align-items-center">
                                    <a href="{{ route('client.myAppointment.show', $appointment) }}"
                                       class="btn btn-primary mr-1">Ver</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center">No se encontraron resultados.</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
        <div class="d-flex flex-row justify-content-end">
            {{ $appointments->links() }}
        </div>
    </div>
@endsection
