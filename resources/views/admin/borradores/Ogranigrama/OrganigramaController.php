<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin\Gerencia;
use App\Admin\SubGerencia;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class OrganigramaController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:organigrama-list');
         $this->middleware('permission:organigrama-create', ['only' => ['create','store']]);
         $this->middleware('permission:organigrama-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:organigrama-delete', ['only' => ['destroy']]);
    }
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SubGerencia::latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
   
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editPermiso">Editar</a>';
   
                           $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletePermiso">Borrar</a>';
    
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('admin.organigramas.index');
    }
     
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Permission::updateOrCreate(['id' => $request->permiso_id],
                ['name' => $request->name]);        
   
        return response()->json(['success'=>'Permiso creado correctamente.']);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permiso = Permission::find($id);
        return response()->json($permiso);
    }
  
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Permission::find($id)->delete();
     
        return response()->json(['success'=>'Permiso eliminado correctamente.']);
    }
    
    public function organigrama()
    {
        $gerencias = SubGerencia::groupBy('gerencias_id')
            ->selectRaw('count(*) as total, gerencias_id')
            ->get();
            
            $consejos = SubGerencia::where('gerencias_id','=',1)->get();
            $presidencias = SubGerencia::where('gerencias_id','=',2)->get();
            $gdts = SubGerencia::where('gerencias_id','=',3)->get();
            $gafs = SubGerencia::where('gerencias_id','=',4)->get();
            $gss = SubGerencia::where('gerencias_id','=',5)->get();
            $gals = SubGerencia::where('gerencias_id','=',6)->get();
            $gpes = SubGerencia::where('gerencias_id','=',7)->get();

        
        return view('admin.organigramas.organigrama', get_defined_vars());
    }
}
