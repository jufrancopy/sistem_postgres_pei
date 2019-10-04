
@extends('layouts.master') 

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-info">
                        <h4 class="card-title ">{{$foda->aspecto}}</h4>
                        <div class="pull-right">
                            <a class="btn btn-warning" href="#"> Volver</a>
                        </div>
                    </div>

                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="card-body">
                    {!! Form::model($foda, ['route'=>['fodas.update', $foda->id],
				        'method'=>'PUT', 'files'=>true])	!!}

					        @include('admin.fodas.partials.fortaleza')

					{!! Form::close() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection