@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center pb-4">
            <div>
                <h1 class="text-primary">Detalle de atención</h1>
                <span
                    class="text-info h3">Paciente: {{ $patient->name }} {{ $patient->last_name }}</span>
            </div>
            <div class="pt-3 pt-md-0">
                <a href="{{ route('doctor.patientMedicalRecord.index', ['patient' => $patient->id]) }}"
                   class="btn btn-primary">
                    <span>Regresar</span>
                </a>
            </div>
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="date">Fecha</label>
            <input id="date" type="text" disabled value="{{ $medicalRecord->created_at }}"
                   class="form-control">
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="doctor">Médico</label>
            <input id="doctor" type="text" disabled
                   value="{{ $medicalRecord->appointment->user->name }} {{ $medicalRecord->appointment->user->last_name }}"
                   class="form-control">
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="medical-speciality">Especialidad</label>
            <input id="medical-speciality" type="text" disabled
                   value="{{ $medicalRecord->appointment->medicalSpeciality->name }}"
                   class="form-control">
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="reason">Razón</label>
            <textarea id="reason" rows="5" disabled
                      class="form-control">{{ $medicalRecord->appointment->reason }}</textarea>
        </div>

        <div class="form-group col-md-6 p-0">
            <label for="diagnosis">Diagnóstico</label>
            <textarea id="diagnosis" rows="5" disabled
                      class="form-control">{{ $medicalRecord->diagnosis }}</textarea>
        </div>

        <div class="table-responsive col-md-6 p-0">
            <label for="medical_exams">Exámenes</label>
            @if($medicalRecord->medicalExams->count())
                <table id="medical_exams" class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">Tipo</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Resultado</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($medicalRecord->medicalExams as $medicalExam)
                        @if($medicalExam->laboratoryExam)
                            <tr>
                                <td class="align-middle">Examen de laboratorio</td>
                                <td class="align-middle">
                                    {{$medicalExam->laboratoryExam->name}}
                                </td>
                                <td class="align-middle">
                                    {{$medicalExam->result ? $resultEnum[$medicalExam->result] : ''}}
                                </td>
                            </tr>
                        @endif
                        @if($medicalExam->imagingExam)
                            <tr>
                                <td class="align-middle">Examen de imagen</td>
                                <td class="align-middle">
                                    {{$medicalExam->imagingExam->name}}
                                </td>
                                <td class="align-middle">
                                    {{$medicalExam->result ? $resultEnum[$medicalExam->result] : ''}}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            @else
                <span class="d-block border-top pt-2 pb-3">No existen exámenes.</span>
            @endif
        </div>

        <div class="table-responsive col-md-6 p-0">
            <label for="recipes">Receta</label>
            @if($medicalRecord->recipes->count())
                <table id="recipes" class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Unidad</th>
                        <th scope="col">Medicina</th>
                        <th scope="col">Prescripción</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($medicalRecord->recipes as $recipe)
                        <tr>
                            <td class="align-middle">
                                {{$recipe->amount}}
                            </td>
                            <td class="align-middle">
                                {{$unitEnum[$recipe->unit]}}
                            </td>
                            <td class="align-middle">
                                {{$recipe->medicine->name}}
                            </td>
                            <td class="align-middle">
                                {{$recipe->prescription}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <span class="d-block border-top pt-2 pb-3">No existe una receta.</span>
            @endif
        </div>
    </div>
@endsection
