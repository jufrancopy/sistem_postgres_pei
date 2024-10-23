@extends('layouts.master')
@section('title', 'Respuestas')

@section('content')
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title ">Respuestas</h4>
        </div>

        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('globales.dashboard') }}">Planificación-Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Módulo Encuestas y Preguntas</li>
            </ol>
        </nav>

        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header text-center bg-primary text-white">
                        <h3>Encuesta</h3>
                    </div>

                    <div class="card-body">
                        @if (empty($answersData))
                            <p>No se encontraron preguntas o respuestas para esta encuesta.</p>
                        @else
                            @foreach ($answersData as $data)
                                <div class="card mb-4">
                                    <div class="card-header">
                                        {!! $data['question'] !!}
                                    </div>
                                    <div class="card-body">
                                        @if (empty($data['options']))
                                            <p>No hay opciones para esta pregunta.</p>
                                        @else
                                            <div class="row">
                                                @foreach ($data['options'] as $option)
                                                    <div class="col-md-6 mb-2">
                                                        <div class="card border @if ($option['is_correct']) border-success @else border-danger @endif">
                                                            <div class="card-body">
                                                                <h5 class="card-title">{{ $option['answer'] }}</h5>
                                                                <div>
                                                                    @if ($option['is_correct'])
                                                                        <span class="badge bg-success">Correcta</span>
                                                                    @else
                                                                        <span class="badge bg-danger">Incorrecta</span>
                                                                    @endif
                                                                    @if (trim($option['answer']) === trim($data['selected_answer'], '"'))
                                                                        <span class="text-primary ms-2">
                                                                            <i class="fas fa-check-circle"></i> Seleccionada
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    
                    
                    
                    
                    



                </div>
            </div>
        </div>

    </div>
@stop

@section('css')

@stop

@section('scripts')

    <script></script>

@stop
