<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Movie;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $movies = Movie::get();
        echo json_encode($movies);
    }

    
    public function store(Request $request)
    {
        $movie = Movie::create($request->all());
        // $movie = new Movie();
        // $movie->name = $request->input('name');
        // $movie->description = $request->input('description');
        // $movie->genre = $request->input('genre');
        // $movie->year = $request->input('year');
        // $movie->duration = $request->input('duration');
        // $movie->save();
        echo json_encode($movie);
        
        
    }

    public function update(Request $request, $id)
    {
        $movie = Movie::find($id);
        $movie->fill($request->all())->save();
        echo json_encode($movie);
        
    }

    
    public function destroy($id)
    {
        $movie = Movie::find($id)->delete();
        echo json_encode($movie);
    }
}
