<?php

namespace App\Http\Controllers\Api;

use App\Alternative;
use App\Exam;
use App\ExamQuestion;
use App\ExamTopic;
use App\Http\Controllers\Api\EstudianteController;
use App\Http\Controllers\Controller;
use App\Question;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Validator;

class ExamenController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }


    /**
     * @param \Illuminate\Http\Request $request
     * @return \Dingo\Api\Http\Response
     */

    public function getByGrade(Request $request)
    {
        $data = $request->input();

        $rules = [
            'grades_id' => 'required|integer',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json(['error' => trans('messages.generic.validation')], 400);
        }

        $objEstudiante = new EstudianteController();
        $user = $objEstudiante->getUserFromRequestToken($request);

        $examenes = Exam::where('students_id',$user->id)
                        ->where('grades_id',$data["grades_id"])
                        ->where('status',1)
                        ->orderBy('created_at')->get();

        return response()->json([
            'data' => $examenes
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Dingo\Api\Http\Response
     */

    public function getExamQuestion(Request $request)
    {

        $data = $request->input();

        $rules = [
            'grades_id' => 'required|integer',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json(['error' => trans('messages.generic.validation')], 400);
        }

        $objEstudiante = new EstudianteController();
        $user = $objEstudiante->getUserFromRequestToken($request);

        $result=[];

        $examenes = Exam::where('students_id',$user->id)
            ->where('grades_id',$data["grades_id"])
            ->where('status',1)
            ->first();

        if (is_null($examenes)) {
            return response()->json(['message' => trans('messages.examen.not_found')], 404);
        }

        /*foreach ($examenes as $examen) {*/

                $questions = [];

                $preguntas = Question::
                    select(
                        'questions.*',
                        'topics.name as topic_name'
                    )
                    ->join('exams_topics', 'exams_topics.id', '=', 'questions.exams_topics_id')
                    ->join('topics', 'topics.id', '=', 'exams_topics.topics_id')
                    ->where('exams_topics.exams_id',$examenes->id)
                    ->where('questions.status',1)
                    //->inRandomOrder()
                    //->limit(5)
                    ->get();

                foreach ($preguntas as $pregunta) {


                    $alternatives = [];
                    $alternativas = Alternative::
                    select(
                        'alternatives.*'
                    )
                        ->where('alternatives.questions_id',$pregunta->id)
                        ->get();

                    foreach ($alternativas as $alternativa) {
                        $alternatives[] = [
                            'id'=>$alternativa->id,
                            'alternative'=>$alternativa->alternative,
                            'name_alternative'=>$alternativa->name_alternative,
                            'is_correct'=>$alternativa->is_correct,
                            'response'=>$alternativa->response,
                        ];
                    }

                    $questions[] = [
                        'id'=>$pregunta->id,
                        'name'=>$pregunta->description,
                        'topic'=>$pregunta->topic_name,
                        'alternatives' => $alternatives
                    ];
                }


            $result = [
                'id'=>$examenes->id,
                'name'=>$examenes->name,
                'questions' => $questions
            ];
        /*}*/

        return response()->json([
            'data' => $result
        ]);

    }

    public function responseExamQuestion($alternativeId,Request $request)
    {
        $alternativa = Alternative::find(0);
        $request['response'] = 1;


        try {
            $alternativa->update($request->all());

            return response()->json([
                'data' => $alternativa
            ]);

        } catch (\Exception $exception) {
            return response()->json(['message' => trans('messages.generic.unprocessable_entity')], 401);
        }


    }

}
