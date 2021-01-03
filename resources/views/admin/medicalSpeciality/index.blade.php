@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex flex-row justify-content-between align-items-center pb-4">
            <h1 class="text-primary">Especialidades médicas</h1>
            <div>
                <a href="{{ route('admin.medicalSpeciality.create') }}" class="btn btn-primary px-4">
                    <span>Crear</span>
                </a>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.medicalSpeciality.index') }}" class="p-0 pb-3 col-md-6">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span id="search-icon" class="input-group-text"><i class="fas fa-search"></i></span>
                </div>
                <input name="search" type="text" placeholder="Ingrese un texto para buscar..."
                       value="{{ $searchValue }}" class="form-control"
                       aria-label="Ingrese un texto para buscar..."
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
                    <th scope="col">Estado</th>
                    <th scope="col">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @if($medicalSpecialities->count())
                    @foreach($medicalSpecialities as $medicalSpeciality)
                        <tr id="medical-speciality-{{ $medicalSpeciality->id }}">
                            <td class="align-middle">{{ $medicalSpeciality->name }}</td>
                            <td class="align-middle">{{ $medicalSpeciality->trashed() ? 'Desactivo' : 'Activo' }}</td>
                            <td class="align-middle col-action">
                                <div class="d-flex flex-row justify-content-end align-items-center">
                                    @if($medicalSpeciality->trashed())
                                        <button class="btn btn-primary"
                                                onclick="toggleTableRow('medical-speciality-'+{{ $medicalSpeciality->id }}, 'restore')">
                                            Activar
                                        </button>
                                    @else
                                        <a href="{{ route('admin.medicalSpeciality.show', $medicalSpeciality) }}"
                                           class="btn btn-primary mr-1">Ver</a>
                                        <a href="{{ route('admin.medicalSpeciality.edit', $medicalSpeciality) }}"
                                           class="btn btn-primary mr-1">Editar</a>
                                        <button class="btn btn-danger"
                                                onclick="toggleTableRow('medical-speciality-'+{{ $medicalSpeciality->id }}, 'destroy')">
                                            Eliminar
                                        </button>
                                    @endif
                                </div>
                            </td>
                            <td id="destroy" colspan="4" class="d-none">
                                <div class="d-flex flex-row justify-content-between align-items-center">
                                    <span>Estás seguro de <strong>Desactivar</strong> la especialidad médica: <strong>{{ $medicalSpeciality->name }}</strong>?</span>
                                    <div class="dialog-destroy-btn">
                                        <form
                                            action="{{ route('admin.medicalSpeciality.destroy', $medicalSpeciality) }}"
                                            method="POST" class="d-inline">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn btn-danger">
                                                <span>Si</span>
                                            </button>
                                        </form>
                                        <button class="btn btn-secondary"
                                                onclick="toggleTableRow('medical-speciality-'+{{ $medicalSpeciality->id }}, 'destroy')">
                                            <span>No</span>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td id="restore" colspan="4" class="d-none">
                                <div class="d-flex flex-row justify-content-between align-items-center">
                                    <span>Estás seguro de <strong>Activar</strong> la especialidad médica: <strong>{{ $medicalSpeciality->name }}</strong>?</span>
                                    <div>
                                        <form
                                            action="{{ route('admin.medicalSpeciality.restore', $medicalSpeciality->id) }}"
                                            method="POST"
                                            class="d-inline">
                                            @method('PUT')
                                            @csrf
                                            <button type="submit" class="btn btn-primary">
                                                <span>Si</span>
                                            </button>
                                        </form>
                                        <button class="btn btn-secondary"
                                                onclick="toggleTableRow('medical-speciality-'+{{ $medicalSpeciality->id }}, 'restore')">
                                            <span>No</span>
                                        </button>
                                    </div>
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
            {{ $medicalSpecialities->links() }}
        </div>
    </div>
@endsection
