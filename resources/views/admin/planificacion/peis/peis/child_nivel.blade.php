<li>{{ $child_nivel->nivel }}
    @foreach ( $child_nivel->dependencies as $dependencia )
    <p class="badge badge-warning"> ({{$dependencia->dependency}})</p>
@endforeach
<a class="btn btn-success btn-circle" href="{{ route('peis-crear-sub-nivel',[$nivelSuperior,$child_nivel->id]) }}"><i class="fa fa-plus"></i></a>
<a class="btn btn-info btn-circle" href="{{ route('peis.edit',$child_nivel->id) }}"><i class="fa fa-edit"></i></a>

{!! Form::open(['route' => ['peis-eliminar-nivel', $nivelSuperior,$child_nivel->id], 'method' => 'DELETE', 'style'=>'display:inline']) !!}
    <button class="btn btn-danger btn-circle" onclick="return confirm('Estas seguro de eliminar la Dependencia {{$child_dependency->dependency}}. Si lo eliminas también eliminarás los datos asociados a el.')">
        <i class="fa fa-trash"></i>
    </button>
{!! Form::close() !!}

</li><hr>
{{-- Si meta no esta en blanco, imprime tabla --}}
@if (!empty($child_nivel->meta))
<table class="table table-striped">
    <thead>
      <tr class="bg-primary">
        <th scope="col">Meta</th>
        <th scope="col">Avance</th>
    </tr>
    </thead>
    <tbody>
      <tr>
        <td>{{$child_nivel->meta }}</td>
        <td>{{$child_nivel->avance }}</td>
      </tr>
     
    </tbody>
  </table>
  @else
  
  @endif
  
@if ($child_nivel->niveles)
  <ul>
      @foreach ($child_nivel->niveles as $childNivel)
          @include('admin.planificacion.peis.peis.child_nivel', ['child_nivel' => $childNivel])
      @endforeach
  </ul>
@endif 