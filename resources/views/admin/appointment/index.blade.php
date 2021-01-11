@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex flex-row justify-content-between align-items-center pb-4">
            <h1 class="text-primary">Citas médicas</h1>
            <div>
                <a href="{{ route('admin.appointment.create') }}" class="btn btn-primary px-4">
                    <span>Agregar</span>
                </a>
            </div>
        </div>

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
                                    <a href="{{ route('admin.appointment.show', $appointment) }}"
                                       class="btn btn-primary mr-1">Ver</a>
                                    <button class="btn btn-danger"
                                            onclick="toggleTableRow('appointment-'+{{ $appointment->id }}, 'destroy')">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                            <td id="destroy" colspan="7" class="d-none">
                                <div class="d-flex flex-row justify-content-between align-items-center">
                                    <span>Estás seguro de <strong>Eliminar</strong> la cita médica del paciente: <strong>{{ $appointment->patient->name }} {{ $appointment->patient->last_name }}</strong>?</span>
                                    <div class="dialog-destroy-btn">
                                        <form
                                            action="{{ route('admin.appointment.destroy', $appointment) }}"
                                            method="POST" class="d-inline">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn btn-danger">
                                                <span>Si</span>
                                            </button>
                                        </form>
                                        <button class="btn btn-secondary"
                                                onclick="toggleTableRow('appointment-'+{{ $appointment->id }}, 'destroy')">
                                            <span>No</span>
                                        </button>
                                    </div>
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
