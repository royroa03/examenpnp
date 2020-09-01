<?php

namespace App\Http\Controllers\Admin;

use App\ExamTopic;
use App\Question;
use App\Http\Controllers\Controller;
use App\Topic;
use Illuminate\Http\Request;
use App\Grade;

class PreguntaController extends Controller
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
        $preguntas = Question::select('questions.*',
                        'exams.name as exam_name',
                        'topics.name as topic_name'
                    )
                ->join('exams_topics', 'exams_topics.id', '=', 'questions.exams_topics_id')
                ->join('exams', 'exams.id', '=', 'exams_topics.exams_id')
                ->join('topics', 'topics.id', '=', 'exams_topics.topics_id')
                ->orderBy('questions.id')
                ->get();
        return view('pregunta.index', compact($preguntas, 'preguntas'));
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $temas_examenes = ExamTopic::select('exams_topics.*',
                                'exams.name as exam_name',
                                'topics.name as topic_name'
                            )
                        ->join('exams', 'exams.id', '=', 'exams_topics.exams_id')
                        ->join('topics', 'topics.id', '=', 'exams_topics.topics_id')
                        ->where('exams_topics.status',1)->orderBy('exams_topics.id')->get();
        return view('pregunta.create', compact($temas_examenes, 'temas_examenes'));
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
            'description' => 'required|string',
            'review' => 'required|string',
            'exams_topics_id' => 'required|integer',
        ]);

        Question::create($request->all());

        return redirect()->route('preguntas.index')
            ->with('success','Pregunta creada correctamente');
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pregunta = Question::find($id);
        $temas_examenes = ExamTopic::select('exams_topics.*',
                    'exams.name as exam_name',
                    'topics.name as topic_name'
                )
            ->join('exams', 'exams.id', '=', 'exams_topics.exams_id')
            ->join('topics', 'topics.id', '=', 'exams_topics.topics_id')
            ->where('exams_topics.status',1)->orderBy('exams_topics.id')->get();
        return view('pregunta.edit', compact($pregunta, 'pregunta',$temas_examenes,'temas_examenes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $pregunta = Question::find($id);

        $request->validate([
            'description' => 'required|string',
            'review' => 'required|string',
            'exams_topics_id' => 'required|integer',
        ]);

        $pregunta->update($request->all());

        return redirect()->route('preguntas.index')
            ->with('success','Pregunta actualizada correctamente');
    }
}
