<?php

namespace App\Http\Controllers\Admin;

use App\Exam;
use App\Http\Controllers\Controller;
use App\Student;
use Illuminate\Http\Request;
use App\Grade;

class ExamenController extends Controller
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
        $examenes = Exam::select('exams.*',
                        'grades.name as grade_name',
                        'students.name as student_name',
                        'students.last_name as student_last_name'
                    )
                ->join('grades', 'grades.id', '=', 'exams.grades_id')
                ->join('students', 'students.id', '=', 'exams.students_id')
                ->orderBy('exams.id')
                ->get();
        return view('examen.index', compact($examenes, 'examenes'));
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $grados = Grade::where('status',1)->orderBy('id')->get();
        $estudiantes = Student::where('status',1)->orderBy('id')->get();
        return view('examen.create', compact($grados, 'grados',$estudiantes,'estudiantes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*if(!is_null($request['date_initial'])){
            $request['date_initial'] = date('Y-m-d H:i:s', strtotime($request['date_initial']));
        }

        if(!is_null($request['date_finish'])){
            $request['date_finish'] = date('Y-m-d H:i:s', strtotime($request['date_finish']));
        }*/

        $request->validate([
            'name' => 'required|string',
            /*'date_initial' => 'nullable|date_format:Y-m-d H:i:s',
            'date_finish' => 'nullable|date_format:Y-m-d H:i:s',*/
            'grades_id' => 'required|integer',
            'students_id' => 'required|integer',
        ]);

        $request['status'] = 1;

        Exam::create($request->all());

        return redirect()->route('examenes.index')
            ->with('success','Examen creado correctamente');
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $examen = Exam::find($id);
        $grados = Grade::where('status',1)->orderBy('id')->get();
        $estudiantes = Student::where('status',1)->orderBy('id')->get();
        return view('examen.edit', compact($examen, 'examen',$grados,'grados',$estudiantes,'estudiantes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $examen = Exam::find($id);

        /*if(!is_null($request['date_initial'])){
            $request['date_initial'] = date('Y-m-d H:i:s', strtotime($request['date_initial']));
        }

        if(!is_null($request['date_finish'])){
            $request['date_finish'] = date('Y-m-d H:i:s', strtotime($request['date_finish']));
        }*/

        $request->validate([
            'name' => 'required|string',
            /*'date_initial' => 'nullable|date_format:Y-m-d H:i:s',
            'date_finish' => 'nullable|date_format:Y-m-d H:i:s',*/
            'grades_id' => 'required|integer',
            'students_id' => 'required|integer',
            'status' => 'required|integer',
        ]);

        $examen->update($request->all());

        return redirect()->route('examenes.index')
            ->with('success','Examen actualizado correctamente');
    }
}
