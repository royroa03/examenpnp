<?php

namespace App\Http\Controllers\Admin;

use App\Alternative;
use App\Exam;
use App\ExamQuestion;
use App\Http\Controllers\Controller;
use App\Question;
use App\Student;
use Illuminate\Http\Request;
use App\Grade;

class AlternativaController extends Controller
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
        $alternativas = Alternative::select('alternatives.*',
                        'questions.description as question_description'
                    )
                ->join('questions', 'questions.id', '=', 'alternatives.questions_id')
                ->orderBy('alternatives.id')
                ->get();
        return view('alternativa.index', compact($alternativas, 'alternativas'));
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $preguntas = Question::select('questions.*',
                    'exams.name as exam_name',
                    'topics.name as topic_name'
                )
                ->join('exams_topics', 'exams_topics.id', '=', 'questions.exams_topics_id')
                ->join('exams', 'exams.id', '=', 'exams_topics.exams_id')
                ->join('topics', 'topics.id', '=', 'exams_topics.topics_id')
                ->where('questions.status',1)
                ->orderBy('questions.id')
                ->get();

        return view('alternativa.create', compact($preguntas,'preguntas'));
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
            'questions_id' => 'required|integer',
            'alternative' => 'required|string',
            'name_alternative' => 'required|string',
            'is_correct' => 'required|boolean',
        ]);

        Alternative::create($request->all());

        return redirect()->route('alternativas.index')
            ->with('success','Alternativa    creada correctamente');
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $alternativa = Alternative::find($id);

        $preguntas = Question::select('questions.*',
            'exams.name as exam_name',
            'topics.name as topic_name'
        )
            ->join('exams_topics', 'exams_topics.id', '=', 'questions.exams_topics_id')
            ->join('exams', 'exams.id', '=', 'exams_topics.exams_id')
            ->join('topics', 'topics.id', '=', 'exams_topics.topics_id')
            ->where('questions.status',1)
            ->orderBy('questions.id')
            ->get();
        return view('alternativa.edit', compact($alternativa, 'alternativa',$preguntas,'preguntas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $alternativa = Alternative::find($id);

        $request->validate([
            'questions_id' => 'required|integer',
            'alternative' => 'required|string',
            'name_alternative' => 'required|string',
            'is_correct' => 'required|boolean',
        ]);

        $alternativa->update($request->all());

        return redirect()->route('alternativas.index')
            ->with('success','Alternativa actualizada correctamente');
    }
}
