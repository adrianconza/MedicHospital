@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center pb-4">
            <div>
                <h1 class="text-primary">Historia médica</h1>
                <span
                    class="text-info h3">Paciente: {{ $patient->name }} {{ $patient->last_name }}</span>
            </div>
            <div class="pt-3 pt-md-0">
                <a href="{{ route('doctor.patientAttended.index') }}" class="btn btn-primary">
                    <span>Regresar</span>
                </a>
            </div>
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
                                    <a href="{{ route('doctor.patientMedicalRecord.show', [$medicalRecord, 'patient' => $patient->id]) }}"
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
