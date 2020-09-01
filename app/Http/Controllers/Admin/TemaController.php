<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Topic;

class TemaController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $temas = Topic::where('status',1)->orderBy('id')->get();
        return view('tema.index', compact($temas, 'temas'));
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tema.create');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        Topic::create($request->all());

        return redirect()->route('temas.index')
            ->with('success','Tema creado correctamente');
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tema = Topic::find($id);
        return view('tema.edit', compact($tema, 'tema'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tema = Topic::find($id);

        $request->validate([
            'name' => 'required|string',
        ]);

        $tema->update($request->all());

        return redirect()->route('temas.index')
            ->with('success','Tema actualizado correctamente');
    }
}
