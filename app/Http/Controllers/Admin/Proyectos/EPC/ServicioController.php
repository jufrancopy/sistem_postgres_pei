<?php

namespace App\Http\Controllers\Admin\Proyectos\EPC;

use App\Admin\Proyecto\EPC\Servicio;
use App\Admin\Proyecto\EPC\Equipamiento;
use App\Admin\Proyecto\EPC\TalentoHumano;
use App\Admin\Proyecto\EPC\MedicamentoInsumo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServicioController extends Controller
{
    public function index(Request $request)
    {
        $servicios = Servicio::all();

        return view('admin.proyectos.epc.servicios.index', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function getForType(Request $request, $type)
    {

        switch ($type) {
            case 'final':
                $servicios = Servicio::where('type', 'final')->orderBy('id', 'DESC')->paginate(10);
                break;
            case 'de_apoyo':
                $servicios = Servicio::where('type', 'de_apoyo')->orderBy('id', 'DESC')->paginate(10);
                break;
            case 'all':
                $servicios = Servicio::orderBy('id', 'DESC')->paginate(10);
                break;
        }

        $data = ['servicios' => $servicios];

        return view('admin.proyectos.epc.servicios.index', get_defined_vars());
    }

    public function create()
    {
        $equipamientos = Equipamiento::orderBy('id', 'ASC')->pluck('item', 'id');
        $tthhs = TalentoHumano::orderBy('id', 'ASC')->pluck('item', 'id');
        
        return view('admin.proyectos.epc.servicios.create', get_defined_vars());
    }

    public function store(Request $request)
    {
        $rules = [
            'item'                          => 'required|min:8',
            'type'                          => 'required',
            'description'                   => 'required',
        ];

        $messages = [
            'item.required'                 => 'El nombre de contener al menos 8 caracteres',
            'item.min'                      => 'El nombre de contener al menos 8 caracteres',
            'type.required'                 => 'Debe especificar el tipo.',
            'description.required'          => 'Describa los detalles del Servicio',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect('proyectos-epc-servicios/create')
                ->withErrors($validator)
                ->withInput();
        }
        
        $equipamientoCantidadAttach = [];
        foreach ($request->equipamientos as $key => $equipamiento) {
            $equipamientoCantidadAttach[$equipamiento] = ['cantidad' => $request->cantidadesEquipamientos[$key]];
        }

        $tthhCantidadAttach = [];
        foreach ($request->tthhs as $key => $tthh) {
            $tthhCantidadAttach[$tthh] = ['cantidad' => $request->cantidadesTthh[$key]];
        }
        
        $infraestructuraCantidadAttach = [];
        foreach ($request->infraestructuras as $key => $infraestructura) {
            $infraestructuraCantidadAttach[$infraestructura] = ['cantidad' => $request->cantidadesInfraestructuras[$key]];
        }

        $otroServicioCantidadAttach = [];
        foreach ($request->otroServicios as $key => $otroServicio) {
            $otroServicioCantidadAttach[$otroServicio] = ['cantidad' => $request->cantidadesOtroServicios[$key]];
        }

        $servicio = Servicio::create($request->all());
        
        $servicio->equipamientos()->attach($equipamientoCantidadAttach);
        $servicio->tthhs()->attach($tthhCantidadAttach);
        $servicio->infraestructuras()->attach($infraestructuraCantidadAttach);
        $servicio->otroServicios()->attach($otroServicioCantidadAttach);


        return redirect()->route('proyectos-epc-servicios.index')
            ->with('success', 'Servicio creado satisfactoriamente');
    }

    public function show($id)
    {
        $servicio = Servicio::findOrFail($id);
        
       return view('admin.proyectos.epc.servicios.show', get_defined_vars());
    }

    public function edit($id)
    {
        $servicio = Servicio::findOrFail($id);
        $equipamientoCantidad = $servicio->equipamientos->toArray();
        $tthhCantidad = $servicio->tthhs->toArray();    
        $infraestructuraCantidad = $servicio->infraestructuras->toArray();
        $otroServicioCantidad = $servicio->otroServicios->toArray();
        
        return view('admin.proyectos.epc.servicios.edit', get_defined_vars());
    }

    public function update(Request $request, $id)
    {
        $equipamientoCantidadSync = [];
        foreach ($request->equipamientos as $key => $equipamiento) {
            $equipamientoCantidadSync[$equipamiento] = ['cantidad' => $request->cantidadesEquipamientos[$key]];
        }

        $tthhCantidadSync = [];
        foreach ($request->tthhs as $key => $tthh) {
            $tthhCantidadSync[$tthh] = ['cantidad' => $request->cantidadesTthh[$key]];
        }

        $infraestructuraCantidadSync = [];
        foreach ($request->infraestructuras as $key => $infraestructura) {
            $infraestructuraCantidadSync[$infraestructura] = ['cantidad' => $request->cantidadesInfraestructuras[$key]];
        }

        $otroServicioCantidadSync = [];
        foreach ($request->otroServicios as $key => $otroServicio) {
            $otroServicioCantidadSync[$otroServicio] = ['cantidad' => $request->cantidadesOtroServicios[$key]];
        }

        $servicio = Servicio::find($id);
        $servicio->fill($request->all())->save();

        $servicio->equipamientos()->sync($equipamientoCantidadSync);
        $servicio->tthhs()->sync($tthhCantidadSync);
        $servicio->infraestructuras()->sync($infraestructuraCantidadSync);
        $servicio->otroServicios()->sync($otroServicioCantidadSync);

        return redirect()->route('proyectos-epc-servicios.show', $servicio->id)
            ->with('info', 'Servicio Actualizado Satisfactoriamente');
    }

    public function destroy($id)
    {
        $servicio = Servicio::find($id);
        $servicio->delete();

        return back()->with('info', 'Servicio eliminado de la Base de Datos');
    }
}
