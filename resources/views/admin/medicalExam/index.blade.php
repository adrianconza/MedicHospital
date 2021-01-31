@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex flex-row justify-content-between align-items-center pb-4">
            <h1 class="text-primary">Exámenes médicos</h1>
        </div>

        <form method="GET" action="{{ route('admin.medicalExam.index') }}" class="p-0 pb-3 col-md-6">
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
                    <th scope="col">Paciente</th>
                    <th scope="col">Médico</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @if(count($medicalExams))
                    @foreach($medicalExams as $medicalExamClave => $medicalExamValor)
                        <tr id="medical-exam-{{ $medicalExamValor->id }}">
                            <td class="align-middle">{{ $medicalExamValor->patient }}</td>
                            <td class="align-middle">{{ $medicalExamValor->doctor }}</td>
                            <td class="align-middle">{{ $medicalExamValor->created_at }}</td>
                            <td class="align-middle col-action">
                                <div class="d-flex flex-row justify-content-end align-items-center">
                                    <a href="{{ route('admin.medicalExam.edit', $medicalExamValor->id) }}"
                                       class="btn btn-primary mr-1">Resultados</a>
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
    </div>
@endsection
