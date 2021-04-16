@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center pb-4">
            <div>
                <h1 class="text-primary">Historia médica</h1>
                <span
                    class="text-info h3">Paciente: {{ $appointment->patient->name }} {{ $appointment->patient->last_name }}</span>
            </div>
            <div class="pt-3 pt-md-0">
                <a href="{{ route('doctor.nextAppointment.create', ['appointment' => $appointment->id, 'patient' => $appointment->patient->id]) }}"
                   class="btn btn-primary">
                    <span>Agendar cita</span>
                </a>
                <a href="{{ route('doctor.medicalRecord.create', ['appointment' => $appointment->id]) }}"
                   class="btn btn-primary">
                    <span>Registrar atención</span>
                </a>
            </div>
        </div>

        <div class="pb-5">
            <h2 class="text-secondary h4">Datos de la cita médica</h2>
            <span class="text-muted d-block pb-2">{{ $appointmentTypeEnum[$appointment->type] }}</span>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="start_time">Fecha inicio</label>
                    <input id="start_time" type="text" disabled value="{{ $appointment->start_time }}"
                           class="form-control">
                </div>
                <div class="form-group col-md-6">
                    <label for="duration">Duración</label>
                    <input id="duration" type="text" disabled value="{{ $appointment->duration }}" class="form-control">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="end_time">Fecha fin</label>
                    <input id="end_time" type="text" disabled value="{{ $appointment->end_time }}" class="form-control">
                </div>
                <div class="form-group col-md-6">
                    <label for="medical-speciality">Especialidad</label>
                    <input id="medical-speciality" type="text" disabled
                           value="{{ $appointment->medicalSpeciality->name }}"
                           class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label for="reason">Razón</label>
                <textarea id="reason" rows="5" disabled class="form-control">{{ $appointment->reason }}</textarea>
            </div>

            <a href="{{ route('doctor.schedule.index') }}" class="btn btn-primary">
                <span>Regresar</span>
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Fecha</th>
                    <th scope="col">Médico</th>
                    <th scope="col">Especialidad</th>
                    <th scope="col">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @if($medicalRecords->count())
                    @foreach($medicalRecords as $medicalRecord)
                        <tr id="medical-record-{{ $medicalRecord->id }}">
                            <td class="align-middle">{{ $medicalRecord->created_at }}</td>
                            <td class="align-middle">{{ $medicalRecord->appointment->user->name }} {{ $medicalRecord->appointment->user->last_name }}</td>
                            <td class="align-middle">{{ $medicalRecord->appointment->medicalSpeciality->name }}</td>
                            <td class="align-middle col-action">
                                <div class="d-flex flex-row justify-content-end align-items-center">
                                    <a href="{{ route('doctor.medicalRecord.show', [$medicalRecord, 'appointment' => $appointment->id]) }}"
                                       class="btn btn-primary mr-1">Ver</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" class="text-center">No se encontraron resultados.</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
        <div class="d-flex flex-row justify-content-end">
            {{ $medicalRecords->links() }}
        </div>
    </div>
@endsection
