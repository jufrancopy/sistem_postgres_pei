<?php

namespace App\Http\Controllers\Admin\Globales;

use App\Http\Controllers\Controller;
use App\Models\Patromony;
use Illuminate\Http\Request;

class PatrimonyController extends Controller
{
    public function index(){
        $patrimonies = Patromony::all();

        return view('admin.globales.patrimonies.index', get_defined_vars());
    }
}
