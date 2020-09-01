<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Student;
use Illuminate\Support\Facades\Hash;

class EstudianteController extends Controller
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
        $estudiantes = Student::where('status',1)->orderBy('id')->get();
        return view('estudiante.index', compact($estudiantes, 'estudiantes'));
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('estudiante.create');
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
            'last_name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'dni' => 'nullable|string',
            'password' => 'required|string',
        ]);

        Student::create([
            'name' => $request['name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'dni' => $request['dni'],
            'password' => Hash::make($request['password']),
        ]);

        return redirect()->route('estudiantes.index')
            ->with('success','Estudiante creado correctamente');
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $estudiante = Student::find($id);
        return view('estudiante.edit', compact($estudiante, 'estudiante'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $estudiante = Student::find($id);

        $request->validate([
            'name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'dni' => 'nullable|string',
        ]);

        $estudiante->update([
            'name' => $request['name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'dni' => $request['dni'],
        ]);

        return redirect()->route('estudiantes.index')
            ->with('success','Estudiante actualizado correctamente');
    }


}
