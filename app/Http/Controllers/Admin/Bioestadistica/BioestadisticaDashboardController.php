<?php

namespace App\Http\Controllers\Admin\Bioestadistica;

use App\Http\Controllers\Controller;

class BioestadisticaDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Administrador|Analista de Bioestadística');
    }

    public function index()
    {
        return view('admin.bioestadistica.dashboard');
    }
}
