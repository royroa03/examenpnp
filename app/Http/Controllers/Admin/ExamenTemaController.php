<?php

namespace App\Http\Controllers\Admin;

use App\Exam;
use App\ExamTopic;
use App\Http\Controllers\Controller;
use App\Question;
use App\Student;
use App\Topic;
use Illuminate\Http\Request;
use App\Grade;

class ExamenTemaController extends Controller
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
        $examenes_temas = ExamTopic::select('exams_topics.*',
                        'exams.name as exam_name',
                        'topics.name as topic_name'
                    )
                ->join('exams', 'exams.id', '=', 'exams_topics.exams_id')
                ->join('topics', 'topics.id', '=', 'exams_topics.topics_id')
                ->orderBy('exams_topics.id')
                ->get();
        return view('examen_tema.index', compact($examenes_temas, 'examenes_temas'));
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $examenes = Exam::where('status',1)->orderBy('id')->get();
        $temas = Topic::where('status',1)->orderBy('id')->get();
        return view('examen_tema.create', compact($examenes, 'examenes',$temas,'temas'));
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
            'exams_id' => 'required|integer',
            'topics_id' => 'required|integer',
        ]);

        ExamTopic::create($request->all());

        return redirect()->route('examenes_temas.index')
            ->with('success','Tema por Examen creado correctamente');
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $examen_tema = ExamTopic::find($id);
        $examenes = Exam::where('status',1)->orderBy('id')->get();
        $temas = Topic::where('status',1)->orderBy('id')->get();
        return view('examen_tema.edit', compact($examen_tema, 'examen_tema',$examenes,'examenes',$temas,'temas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $examen_tema = ExamTopic::find($id);

        $request->validate([
            'exams_id' => 'required|integer',
            'topics_id' => 'required|integer',
            'status' => 'required|integer',
        ]);

        $examen_tema->update($request->all());

        return redirect()->route('examenes_temas.index')
            ->with('success','Tema por Examen actualizado correctamente');
    }
}
