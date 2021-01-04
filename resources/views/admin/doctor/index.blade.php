@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex flex-row justify-content-between align-items-center pb-4">
            <h1 class="text-primary">Médicos</h1>
            <div>
                <a href="{{ route('admin.doctor.create') }}" class="btn btn-primary px-4">
                    <span>Crear</span>
                </a>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.doctor.index') }}" class="p-0 pb-3 col-md-6">
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
                    <th scope="col">Estado</th>
                    <th scope="col">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @if($users->count())
                    @foreach($users as $user)
                        <tr id="user-{{ $user->id }}">
                            <td class="align-middle">{{ $user->name }} {{ $user->last_name }}</td>
                            <td class="align-middle">{{ $user->identification }}</td>
                            <td class="align-middle">{{ $user->email }}</td>
                            <td class="align-middle">{{ $user->phone }}</td>
                            <td class="align-middle">{{ $user->trashed() ? 'Desactivo' : 'Activo' }}</td>
                            <td class="align-middle col-action">
                                <div class="d-flex flex-row justify-content-end align-items-center">
                                    @if($user->trashed())
                                        <button class="btn btn-primary"
                                                onclick="toggleTableRow('user-'+{{ $user->id }}, 'restore')">
                                            Activar
                                        </button>
                                    @else
                                        <a href="{{ route('admin.doctor.show', $user) }}"
                                           class="btn btn-primary mr-1">Ver</a>
                                        <a href="{{ route('admin.doctor.edit', $user) }}"
                                           class="btn btn-primary mr-1">Editar</a>
                                        <button class="btn btn-danger"
                                                onclick="toggleTableRow('user-'+{{ $user->id }}, 'destroy')">
                                            Eliminar
                                        </button>
                                    @endif
                                </div>
                            </td>
                            <td id="destroy" colspan="6" class="d-none">
                                <div class="d-flex flex-row justify-content-between align-items-center">
                                    <span>Estás seguro de <strong>Desactivar</strong> el médico: <strong>{{ $user->name }} {{ $user->last_name }}</strong>?</span>
                                    <div class="dialog-destroy-btn">
                                        <form
                                            action="{{ route('admin.doctor.destroy', $user) }}"
                                            method="POST" class="d-inline">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn btn-danger">
                                                <span>Si</span>
                                            </button>
                                        </form>
                                        <button class="btn btn-secondary"
                                                onclick="toggleTableRow('user-'+{{ $user->id }}, 'destroy')">
                                            <span>No</span>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td id="restore" colspan="6" class="d-none">
                                <div class="d-flex flex-row justify-content-between align-items-center">
                                    <span>Estás seguro de <strong>Activar</strong> el médico: <strong>{{ $user->name }} {{ $user->last_name }}</strong>?</span>
                                    <div>
                                        <form
                                            action="{{ route('admin.doctor.restore', $user->id) }}"
                                            method="POST"
                                            class="d-inline">
                                            @method('PUT')
                                            @csrf
                                            <button type="submit" class="btn btn-primary">
                                                <span>Si</span>
                                            </button>
                                        </form>
                                        <button class="btn btn-secondary"
                                                onclick="toggleTableRow('user-'+{{ $user->id }}, 'restore')">
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
            {{ $users->links() }}
        </div>
    </div>
@endsection
