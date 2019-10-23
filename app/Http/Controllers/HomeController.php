<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Admin\Foda\FodaAspecto;
use App\Admin\Foda\FodaCategoria;
use App\Admin\Foda\FodaPerfil;
use App\Admin\Foda\FodaAnalisis;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $totalPerfiles = FodaPerfil::all();
        $totalPerfiles = count($totalPerfiles);
        
        return view('home', get_defined_vars());
    }
}
