@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-primary pb-3">Agendar una cita médica</h1>
        <form action="{{ route('client.myAppointment.create') }}" method="GET" class="form">
            <div class="form-group col-md-6 p-0">
                <label for="day_appointment">Fecha *</label>
                <input id="day_appointment" name="day_appointment" type="text" placeholder="Ingresa la fecha"
                       min="{{date('Y-m-d')}}" value="{{ $dayAppointment }}" class="form-control"
                       onfocus="(this.type='date')" aria-describedby="validation-birthday">
            </div>

            <div class="form-group col-md-6 p-0">
                <label for="medical_speciality">Especialidad médica *</label>
                <select id="medical_speciality" name="medical_speciality"
                        class="form-control" aria-describedby="validation-city">
                    <option value="">Selecciona la especialidad médica</option>
                    @foreach($medicalSpecialities as $medicalSpeciality)
                        <option
                            value="{{ $medicalSpeciality->id }}" {{ +$medicalSpecialityId === +$medicalSpeciality->id ? 'selected' : '' }}>{{ $medicalSpeciality->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-6 p-0">
                <label for="doctor">Médico</label>
                <select id="doctor" name="doctor" class="form-control">
                    <option value="">Selecciona un médico</option>
                    @if($doctors)
                        @foreach($doctors as $doctor)
                            <option
                                value="{{ $doctor->id }}" {{ +$doctorId === +$doctor->id ? 'selected' : '' }}>{{ $doctor->name }} {{ $doctor->last_name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="pt-3">
                <a href="{{ route('client.myAppointment.index') }}" class="btn btn-secondary">
                    <span>Cancelar</span>
                </a>
                <button type="submit" class="btn btn-primary">
                    <span>Buscar</span>
                </button>
            </div>
        </form>

        <div class="table-responsive pt-5">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Fecha inicio</th>
                    <th scope="col">Fecha fin</th>
                    <th scope="col">Duración</th>
                    <th scope="col">Médico</th>
                    <th scope="col">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @if($appointments && count($appointments) > 0)
                    @foreach($appointments as $appointment)
                        <tr>
                            <td class="align-middle">{{ $appointment->start_time }}</td>
                            <td class="align-middle">{{ $appointment->end_time }}</td>
                            <td class="align-middle">{{ $appointment->duration }}</td>
                            <td class="align-middle">{{ $appointment->doctor }}</td>
                            <td class="align-middle col-action">
                                <div class="d-flex flex-row justify-content-end align-items-center">
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#scheduleModal"
                                            data-start-time="{{$appointment->start_time}}"
                                            data-end-time="{{$appointment->end_time}}"
                                            data-medical-speciality-id="{{$appointment->medical_speciality_id}}"
                                            data-medical-speciality="{{$appointment->medical_speciality}}"
                                            data-doctor-id="{{$appointment->doctor_id}}"
                                            data-doctor="{{$appointment->doctor}}">
                                        Agendar
                                    </button>
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

        <div id="scheduleModal" tabindex="-1" class="modal fade" aria-labelledby="scheduleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Agendar cita</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('client.myAppointment.store') }}" method="POST" class="form">
                        @csrf

                        <div class="modal-body">
                            <div class="form-group">
                                <label for="start_time_show">Fecha inicio</label>
                                <input id="start_time_show" type="text" disabled class="form-control">
                                <input id="start_time" name="start_time" type="hidden">
                            </div>

                            <div class="form-group">
                                <label for="end_time_show">Fecha fin</label>
                                <input id="end_time_show" type="text" disabled class="form-control">
                                <input id="end_time" name="end_time" type="hidden">
                            </div>

                            <div class="form-group">
                                <label for="medical_speciality_show">Especialidad médica</label>
                                <input id="medical_speciality_show" type="text" disabled class="form-control">
                                <input id="medical_speciality" name="medical_speciality" type="hidden">
                            </div>

                            <div class="form-group">
                                <label for="doctor_show">Médico</label>
                                <input id="doctor_show" type="text" disabled class="form-control">
                                <input id="doctor" name="doctor" type="hidden">
                            </div>

                            <div class="form-group">
                                <label for="patient">Paciente *</label>
                                <select id="patient" name="patient"
                                        class="form-control @error('medical_speciality') is-invalid @enderror"
                                        aria-describedby="validation-city">
                                    <option value="">Selecciona el paciente</option>
                                    @if(isset($patients))
                                        @foreach($patients as $patient)
                                            <option
                                                value="{{ $patient->id }}" {{ +old('patient') === +$patient->id ? 'selected' : '' }}>{{ $patient->name }} {{ $patient->last_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('patient')
                                <div id="validation-city" class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="reason">Razón *</label>
                                <textarea id="reason" name="reason" rows="5" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Agendar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
