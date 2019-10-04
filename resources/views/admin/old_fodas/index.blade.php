@extends('layouts.master')

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
                @endif

                <div class="card">
                    <div class="card-header card-header-info">
                        <h4 class="card-title ">Módulo FODA</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><a href="{{route('foda-perfiles.index')}}">Perfiles</a></li>
                            <li class="list-group-item"><a href="#">Categorias</a></li>
                            <li class="list-group-item"><a href="#">Aspectos</a></li>
                        </ul>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
</div>
</div>

@endsection