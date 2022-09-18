<li>{{ $child_dependency->dependency }}
<a class="btn btn-success btn-circle" href="{{ route('globales.organigramas-crear-subdependencia',$child_dependency->id) }}"><i class="fa fa-plus"></i></a>
<a class="btn btn-info btn-circle" href="{{ route('globales.organigramas-editar-subdependencia',$child_dependency->id) }}"><i class="fa fa-edit"></i></a>
{!! Form::open(['route' => ['globales.organigramas.destroy', $child_dependency->id], 'method' => 'DELETE', 'style'=>'display:inline']) !!}
    <button class="btn btn-danger btn-circle" onclick="return confirm('Estas seguro de eliminar la Dependencia {{$child_dependency->dependency}}. Si lo eliminas también eliminarás los datos asociados a el.')">
        <i class="fa fa-trash"></i>
    </button>
{!! Form::close() !!}
</li>
@if ($child_dependency->children)
    <ul>
        @foreach ($child_dependency->children as $childDependency)
            @include('admin.globales.organigramas.child_dependency', ['child_dependency' => $childDependency])
        @endforeach
    </ul>
@endif