@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center pb-4">
            <div>
                <h1 class="text-primary">Registrar atención</h1>
                <span
                    class="d-block text-info h3">Paciente: {{ $patient->name }} {{ $patient->last_name }}</span>
            </div>
            <div class="pt-3 pt-md-0">
                <a href="{{ route('doctor.medicalRecord.index', ['appointment' => $appointment]) }}"
                   class="btn btn-secondary">
                    <span>Regresar</span>
                </a>
                <button type="submit" form="medical-record" class="btn btn-primary">
                    <span>Guardar</span>
                </button>
            </div>
        </div>


        <form id="medical-record" action="{{ route('doctor.medicalRecord.store') }}" method="POST" class="form">
            @csrf

            <input name="appointment" type="hidden" value="{{ $appointment->id }}">
            <div class="form-group p-0">
                <label for="diagnosis">Diagnóstico *</label>
                <textarea id="diagnosis" name="diagnosis" rows="5" placeholder="Ingresa el diagnóstico" autofocus
                          class="form-control @error('diagnosis') is-invalid @enderror"
                          aria-describedby="validation-name">{{ old('diagnosis') }}</textarea>
                @error('diagnosis')
                <div id="validation-name" class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start">
                <div class="col-md-6 p-0 pr-md-3">
                    <h4>Exámenes</h4>
                    <div class="input-group">
                        <select id="exam" class="form-control">
                            <option value="">Selecciona un examen</option>
                            @foreach($exams as $exam)
                                <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-primary"
                                    onclick="addSelectToRow('exam', 'exams', 'exams')">
                                Añadir
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="exams" class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col">Nombre</th>
                                <th scope="col">Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(old('exams'))
                                @foreach(old('exams') as $exam_id)
                                    <tr id="exams-{{$exam_id}}">
                                        <td class="d-none">
                                            <input name="exams[]" type="hidden"
                                                   value="{{$exam_id}}">
                                        </td>
                                        <td class="align-middle">
                                            @foreach($exams as $exam)
                                                @if($exam->id === $exam_id)
                                                    {{$exam->name}}
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="align-middle col-action">
                                            <div class="d-flex flex-row justify-content-end align-items-center">
                                                <button type="button" class="btn btn-danger"
                                                        onclick="removeSelectToRow('exams', 'exams-{{$exam_id}}')">
                                                    Eliminar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                    @error('exams')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="table-responsive col-md-6 p-0 pl-md-3">
                    <h4>Receta</h4>
                    <div class="pb-3">
                        <div class="form-group p-0">
                            <label for="medicine">Medicina *</label>
                            <select id="medicine" class="form-control">
                                <option value="">Selecciona una medicina</option>
                                @foreach($medicines as $medicine)
                                    <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="amount">Cantidad *</label>
                                <input id="amount" type="number" step="0.01" min="0" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="unit">Unidad *</label>
                                <select id="unit" class="form-control">
                                    <option value="">Selecciona una unidad</option>
                                    @foreach($unitEnum as $clave => $valor)
                                        <option value="{{ $clave }}">{{ $valor }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group p-0">
                            <label for="prescription">Prescripción *</label>
                            <textarea id="prescription" rows="3" placeholder="Ingresa la prescripción"
                                      class="form-control"></textarea>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="addRecipeToRow()">
                            Añadir
                        </button>
                    </div>
                    <table id="medicines" class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">Cantidad</th>
                            <th scope="col">Unidad</th>
                            <th scope="col">Medicina</th>
                            <th scope="col">Prescripción</th>
                            <th scope="col">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(old('medicines'))
                            @for ($i = 0; $i < count(old('medicines')); $i++)
                                The current value is {{ $i }}
                                <tr id="medicines-{{old('medicines')[$i]}}">
                                    <td class="d-none">
                                        <input name="amounts[]" type="hidden"
                                               value="{{old('amounts')[$i]}}">
                                        <input name="units[]" type="hidden"
                                               value="{{old('units')[$i]}}">
                                        <input name="medicines[]" type="hidden"
                                               value="{{old('medicines')[$i]}}">
                                        <input name="prescriptions[]" type="hidden"
                                               value="{{old('prescriptions')[$i]}}">
                                    </td>
                                    <td class="align-middle">{{old('amounts')[$i]}}</td>
                                    <td class="align-middle">{{$unitEnum[old('units')[$i]]}}</td>
                                    <td class="align-middle">
                                        @foreach($medicines as $medicine)
                                            @if(+$medicine->id === +old('medicines')[$i] || $medicine->id === old('medicines')[$i])
                                                {{$medicine->name}}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="align-middle">{{old('prescriptions')[$i]}}</td>
                                    <td class="align-middle col-action">
                                        <div class="d-flex flex-row justify-content-end align-items-center">
                                            <button type="button" class="btn btn-danger"
                                                    onclick="removeSelectToRow('medicines', 'medicines-{{old('medicines')[$i]}}')">
                                                Eliminar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endfor
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>
@endsection
