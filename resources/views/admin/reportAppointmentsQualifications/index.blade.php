@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-primary pb-3">Reporte general de citas médicas y calificaciones</h1>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Médico</th>
                    <th scope="col" class="text-center">Total de citas</th>
                    <th scope="col" class="text-center">Promedio de calificación</th>
                </tr>
                </thead>
                <tbody>
                @if($report && count($report) > 0)
                    @foreach($report as $doctor)
                        <tr id="doctor-{{ $doctor->id }}">
                            <td class="align-middle">{{ $doctor->name }} {{ $doctor->last_name }}</td>
                            <td class="align-middle text-center">{{ $doctor->num_appointments }}</td>
                            <td class="align-middle text-center">{{ $doctor->avg_qualify }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="3" class="text-center">No se encontraron resultados.</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
