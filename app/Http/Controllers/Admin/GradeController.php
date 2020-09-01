<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Grade;

class GradeController extends Controller
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
        $grados = Grade::where('status',1)->orderBy('id')->get();
        return view('grado.index', compact($grados, 'grados'));
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('grado.create');
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

        Grade::create($request->all());

        return redirect()->route('grados.index')
            ->with('success','Grado creado correctamente');
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $grado = Grade::find($id);
        return view('grado.edit', compact($grado, 'grado'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $grado = Grade::find($id);

        $request->validate([
            'name' => 'required|string',
        ]);

        $grado->update($request->all());

        return redirect()->route('grados.index')
            ->with('success','Grado actualizado correctamente');
    }
}
