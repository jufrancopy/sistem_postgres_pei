@extends('layouts.app', ['class' => 'off-canvas-sidebar', 'activePage' => 'home', 'title' => __('Material Dashboard')])

@section('content')
    <div class="container" style="height: 100vh; display: flex; justify-content: center; align-items: center;">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-8 text-center">
                <h1 class="text-white">
                    {{ __('Bienvenidos a SIPLAN') }}</h1>

                <a href="{{ route('login') }}" class="btn btn-info text-white">Ingresar</a>
            </div>
        </div>
    </div>
@endsection
