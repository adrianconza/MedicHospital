@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-primary">Ingresar resultados de exámenes médicos</h1>
        <span
            class="d-block text-info h3 pb-3">Paciente: {{ $patient->name }} {{ $patient->last_name }}</span>

        <form action="{{ route('admin.medicalExam.update', $appointmentId) }}" method="POST" class="form">
            @method('PUT')
            @csrf

            <table id="medicines" class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Tipo</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Resultado</th>
                </tr>
                </thead>
                <tbody>
                @foreach($medicalExams as $medicalExamClave => $medicalExamValor)
                    @if($medicalExamValor->laboratory_exam_id)
                        <tr>
                            <td class="d-none">
                                <input name="ids[]" type="hidden" value="{{$medicalExamValor->id}}">
                            </td>
                            <td class="align-middle">Examen de laboratorio</td>
                            <td class="align-middle">
                                {{$medicalExamValor->laboratory_exam_name}}
                            </td>
                            <td class="align-middle">
                                <select id="results" name="results[]" class="form-control">
                                    <option value="">Selecciona un resultado</option>
                                    @foreach($resultsEnum as $clave => $valor)
                                        <option value="{{ $clave }}">{{ $valor }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    @endif
                    @if($medicalExamValor->imaging_exam_id)
                        <tr>
                            <td class="d-none">
                                <<input name="ids[]" type="hidden" value="{{$medicalExamValor->id}}">
                            </td>
                            <td class="align-middle">Examen de imagen</td>
                            <td class="align-middle">
                                {{$medicalExamValor->imaging_exam_name}}
                            </td>
                            <td class="align-middle">
                                <select id="results" name="results[]" class="form-control">
                                    <option value="">Selecciona un resultado</option>
                                    @foreach($resultsEnum as $clave => $valor)
                                        <option value="{{ $clave }}">{{ $valor }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>

            <div class="pt-3">
                <a href="{{ route('admin.medicalExam.index') }}" class="btn btn-secondary">
                    <span>Cancelar</span>
                </a>
                <button type="submit" class="btn btn-primary">
                    <span>Guardar</span>
                </button>
            </div>
        </form>
    </div>
@endsection
