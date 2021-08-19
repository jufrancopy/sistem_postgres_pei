<li>{{ $child_clasificador->clasificador }}
<a class="badge badge-success" href="{{ route('formulario-clasificadores-crear-subclasificador',$child_clasificador->id) }}"><i class="fa fa-plus"></i></a>
<a class="badge badge-info" href="{{ route('formulario-clasificadores-editar-subclasificador',$child_clasificador->id) }}"><i class="fa fa-edit"></i></a>
{!! Form::open(['route' => ['formulario-clasificadores.destroy', $child_clasificador->id], 'method' => 'DELETE', 'style'=>'display:inline']) !!}
    <button class="badge badge-danger" onclick="return confirm('Estas seguro de eliminar el Clasificador {{$child_clasificador->clasificador}}. Si lo eliminas también eliminarás los datos asociados a el.')">
        <i class="fa fa-trash"></i>
    </button>
{!! Form::close() !!}
</li>
@if ($child_clasificador->clasificadores)
    <ul>
        @foreach ($child_clasificador->clasificadores as $childClasificador)
            @include('admin.globales.formularios.clasificadores.child_clasificador', ['child_clasificador' => $childClasificador])
        @endforeach
    </ul>
@endif