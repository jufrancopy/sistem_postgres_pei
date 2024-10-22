<?php

namespace App\Http\Controllers\Admin\Globales\Survey;

use App\Http\Controllers\Controller;
use App\Models\Admin\Globales\Question;
use App\Models\Admin\Globales\Survey;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;


class SurveyController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Survey::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-circle editSurvey" ><i class="far fa-edit"></i></a>';

                    $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-circle deleteSurvey"><i class="fa fa-trash" aria-hidden="true"></i></a>';

                    $btn .= ' <a href="' . route('surveys.show', $row->id) . '" data-toggle="tooltip" data-original-title="Show" class="btn btn-warning btn-circle addQuestion"><i class="fa fa-list-ol" aria-hidden="true"></i></a>';

                    return $btn;
                })

                ->addColumn('analysts', function (Survey $survey) {
                    $analystNames = $survey->analysts->pluck('name')->implode(', ');
                    return $analystNames;
                })

                ->addColumn('participants', function (Survey $survey) {
                    $participantNames = $survey->participants->pluck('name')->implode(', ');
                    return $participantNames;
                })

                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.surveys.index');
    }

    public function edit($id)
    {
        $survey = Survey::with(['analysts'])->find($id);

        $analystsChecked = [];

        foreach ($survey->analysts as $analyst) {
            $analystsChecked[] = ['id' => $analyst->id, 'text' => $analyst->name];
        }

        return response()->json([
            'survey' => $survey,
            'analystsChecked' => $analystsChecked
        ]);
    }

    public function show($id)
    {
        $survey = Survey::find($id);

        return view('admin.surveys.show', compact('survey'));
    }

    public function showDetails($id)
    {
        $survey = Survey::find($id);

        return response()->json([
            'html' => view('admin.surveys.partials.questions', compact('survey'))->render()
        ]);
    }

    public function showQuestions($surveyID)
    {
        $survey = Survey::with('questions')->findOrFail($surveyID);

        // Obtener todas las preguntas y sus respuestas
        $questions = $survey->questions->map(function ($question) {
            // Obtener las respuestas en formato JSON de la tabla pivot
            $answersJson = DB::table('answers_has_questions')
                ->where('question_id', $question->id)
                ->value('answers');  // Obtén el campo 'answers' que contiene el JSON

            // Decodificar el JSON a un array
            $answersArray = json_decode($answersJson, true);

            // Devolver la pregunta junto con las respuestas
            return [
                'id' => $question->id,
                'survey_id' => $question->survey_id,
                'question' => $question->question,
                'created_at' => $question->created_at,
                'updated_at' => $question->updated_at,
                'answers' => $answersArray,  // Añadir respuestas decodificadas
            ];
        });


        return $questions;

        return response()->json($questions);
    }

    public function showQuestionsTemplate(Request $request, $surveyID)
    {
        $survey = Survey::with('questions')->findOrFail($surveyID);

        return view('admin.surveys.answers.index', compact('survey'));
    }

    public function checkAnswer(Request $request, $surveyId)
    {
        $request->validate([
            'answer' => 'required|string',
            'question_id' => 'required|integer',
        ]);

        // Encuentra la pregunta y obtén las respuestas
        $question = Question::with('getAnswers')->find($request->question_id);
        return $question->getAnswers;
        // Decodifica las respuestas almacenadas en formato JSON
        $answers = json_decode($question->getAnswers->answers, true);

        // Verifica si la respuesta seleccionada es correcta
        $isCorrect = false;
        foreach ($answers as $answer) {
            if ($answer['answer'] === $request->answer && $answer['is_correct'] == 1) {
                $isCorrect = true;
                break;
            }
        }

        // Retorna si la respuesta fue correcta
        return response()->json(['isCorrect' => $isCorrect]);
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $request->validate(
                [
                    'name'   => 'required',
                    'type'      => 'required',
                    'description'         => 'required',
                ],
                [
                    'name.required'     => 'El nombre es requerido',
                    'type.required'     => 'El tipo es requerido',
                    'description.required'     => 'Indique el Tipo',
                ]
            );
        };

        $survey = Survey::updateOrCreate(
            ['id' => $request->profile_id],
            [
                'type' => $request->type,
                'name' => $request->name,
                'description' => $request->description,
            ]
        );
        $survey->analysts()->sync($request->analyst_id);
        $survey->participants()->sync($request->participant_id);

        return response()->json(['success' => 'Encuesta creada satisfactoriamente']);
    }

    public function destroy(Request $request, $id)
    {
        $survey = Survey::find($id)->delete();

        return response()->json([$survey]);
    }
}
