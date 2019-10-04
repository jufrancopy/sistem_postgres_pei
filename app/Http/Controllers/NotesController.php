<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotesController extends Controller
{
    public function index(){
    	$notes= \App\Note::all();

    	return view('notes.index', compact('notes'));
    }

   public function destroy($id)
	{
	    \App\Note::findOrFail($id)->delete();

	    return redirect()->back();
	} 
}
