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
                <a href="{{ route('client.myPatient.index') }}" class="btn btn-primary">
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
                    <th scope="col">Calificación</th>
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
                            <td class="align-middle">{{ $medicalRecord->qualify ? $qualifyEnum[$medicalRecord->qualify] : ''}}</td>
                            <td class="align-middle col-action">
                                <div class="d-flex flex-row justify-content-end align-items-center">
                                    <a href="{{ route('client.myMedicalRecord.show', [$medicalRecord, 'patient' => $patient->id]) }}"
                                       class="btn btn-primary mr-1">Ver</a>
                                    @if(!isset($medicalRecord->qualify))
                                        <button class="btn btn-primary" data-toggle="modal"
                                                data-target="#qualifyModal" data-medical-record-id="{{$medicalRecord->id}}">
                                            Calificar
                                        </button>
                                    @endif
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
            {{ $medicalRecords->links() }}
        </div>

        <div id="qualifyModal" tabindex="-1" class="modal fade" aria-labelledby="qualifyModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Calificar cita</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('client.myMedicalRecord.update', 0) }}" method="POST"
                          class="form">
                        @method('PUT')
                        @csrf

                        <input id="medical_record_id" name="medical_record_id" type="hidden">
                        <input name="patient" type="hidden" value="{{ $patient->id }}">

                        <div class="modal-body">
                            <div class="form-group">
                                <label for="qualify">Calificación</label>
                                <select id="qualify" name="qualify" class="form-control">
                                    <option value="">Selecciona una calificación</option>
                                    @foreach($qualifyEnum as $clave => $valor)
                                        <option value="{{ $clave }}">{{ $valor }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Calificar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
