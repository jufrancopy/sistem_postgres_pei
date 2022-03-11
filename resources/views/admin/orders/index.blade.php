@extends('layouts.master')
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                @if ($message = Session::get('success'))
                <div class="alert alert-info">
                    <p>{{ $message }}</p>
                </div>
                @endif
            </div>
            <div class="card">
                <div class="card-header card-header-info">
                    <h4 class="card-title ">Ordenes</h4>
                    <a class="btn btn-success" href="{{ route('orders.create') }}">Agregar</a>
                    <div class="pull-right">
                        <a class="btn btn-warning pull-right" href="{{ route('orders.index') }}"> Atras</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <!-- AquiBuscador -->
                            <div class="float-right">
                                {!! Form::open(['route' => 'organigramas-listado','method' => 'GET',
                                'class'=>'navbar-form navbar-left pull-right','role'=>'search']) !!}
                                <div class="form-group">
                                    {!! Form::text('nombre',null, ['class'=>'form-control','placeholder'=>'Buscar
                                    Organigrama']) !!}
                                </div>

                                <button type="submit" class="btn btn-default pull-right">Buscar</button>

                            </div>

                            {!!Form::close()!!}
                            <!-- Fin Buscador -->
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>user</th>
                                    <th>correo</th>
                                    <th>Costo</th>
                                    <th width="280px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr data-entry-id="{{ $order->id }}">
                                    <td>
                                        {{ $order->id ?? '' }}
                                    </td>
                                    <td>
                                        {{ $order->customer_name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $order->customer_email ?? '' }}
                                    </td>
                                    <td>
                                        <ul>
                                            @foreach($order->products as $item)
                                            <li>{{ $item->name }} ({{ $item->pivot->quantity }} x ${{ $item->price }})
                                            </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        {{-- ... buttons ... --}}
                                    </td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection