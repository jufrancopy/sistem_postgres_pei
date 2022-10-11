<li>{{ $child_item->name }}
    <a class="btn btn-success btn-circle" href="{{ route('globales.variables-crear-item',$child_item->id) }}"><i
            class="fa fa-plus"></i></a>
    <a class="btn btn-info btn-circle" href="{{ route('globales.variables-editar-item',$child_item->id) }}"><i
            class="fa fa-edit"></i></a>
    {!! Form::open(['route' => ['globales.variables.destroy', $child_item->id], 'method' => 'DELETE',
    'style'=>'display:inline']) !!}
    <button class="btn btn-danger btn-circle"
        onclick="return confirm('Estas seguro de eliminar  {{$child_item->name}}. Si lo eliminas también eliminarás los datos asociados a el.')">
        <i class="fa fa-trash"></i>
    </button>
    {!! Form::close() !!}
    @switch($child_item->type)
    @case($child_item->type == 'level')
    <span class="badge badge-danger">Nivel</span>
    @break
    @case($child_item->type == 'service')
    <span class="badge badge-danger">Servicio</span>
    @break

    @case($child_item->type == 'require')
    <span class="badge badge-warning">Requerimiento</span>
    @break

    @case($child_item->type == 'item')
    <span class="badge badge-info">Item</span>
    @break

    @case($child_item->type == 'response')
    <span class="badge badge-success">Respuesta</span>
    @break

    @default
    <span>Sin Tipo</span>
    @endswitch
</li>
@if ($child_item->children)
<ul>
    @foreach ($child_item->children as $childItem)
    @include('admin.globales.formularios.variables.child_item', ['child_item' => $childItem])
    @endforeach
</ul>
@endif