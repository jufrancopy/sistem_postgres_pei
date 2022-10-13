@extends('layouts.master')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-info">
                            <h4 class="panel-title">{{ $data['title'] }}</h4>
                        </div>
                        <div class="card-body">
                            @include('partials.errors')
                            @if (!empty($category))
                                {!! Form::model($category, $header) !!}
                            @else
                                {!! Form::open($header) !!}
                            @endif
                            <div class="form-group">
                                {!! Form::label('Nombre de categoría') !!}
                                {!! Form::text('name', null, [
                                    'class' => 'form-control',
                                    'placeholder' => 'Ingresa nombre de categoría',
                                    'required' => true,
                                ]) !!}
                            </div>
                            {!! Form::submit($data['button'], ['class' => 'btn btn-primary']) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
