<?php

namespace App\Http\Controllers\Admin\Survey;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function index()
    {
        return view('admin.surveys.index');
    }
}
