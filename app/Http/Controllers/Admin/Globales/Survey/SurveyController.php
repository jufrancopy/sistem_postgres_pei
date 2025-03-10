<?php

namespace App\Http\Controllers\Admin\Globales\Survey;

use App\Admin\Globales\Group;
use App\Admin\Planificacion\Task\Task;
use App\Admin\Planificacion\Task\TypeTask;
use App\Http\Controllers\Controller;
use App\Models\Admin\Globales\Question;
use App\Models\Admin\Globales\Survey;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

                ->addColumn('group', function (Survey $survey) {
                    return $survey->group ? $survey->group->name : '';
                })


                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.surveys.index');
    }

    public function edit($id)
    {
        $survey = Survey::with(['analysts', 'group', 'group.parent', 'dependency'])->find($id);

        $parentID = $survey->group->parent->id;
        $parentName = $survey->group->parent->name;

        $analystsChecked = [];

        foreach ($survey->analysts as $analyst) {
            $analystsChecked[] = ['id' => $analyst->id, 'text' => $analyst->name];
        }

        return response()->json([
            'survey' => $survey,
            'analystsChecked' => $analystsChecked,
            'parentID' => $parentID,
            'parentName' => $parentName,
        ]);
    }

    public function detailAnswer(Request $request, $surveyID)
    {
        // Obtén todas las preguntas de la encuesta
        $questions = Question::where('survey_id', $surveyID)->get();

        // Inicializa un arreglo para almacenar la información
        $answersData = [];
        $chartData = [];

        // Recorre cada pregunta y busca las respuestas y opciones asociadas
        foreach ($questions as $question) {
            // Obtener la respuesta seleccionada por el usuario
            $selectedAnswer = DB::table('answers')
                ->where('question_id', $question->id)
                ->where('participant_id', auth()->user()->id)
                ->first();

            // Obtener las posibles respuestas de la tabla pivot
            $answersDataRow = DB::table('answers_has_questions')
                ->where('question_id', $question->id)
                ->first();

            // Decodifica el JSON que contiene las respuestas
            $possibleAnswers = $answersDataRow ? json_decode($answersDataRow->answers, true) : [];

            // Agrega la pregunta y sus opciones al arreglo
            $answersData[] = [
                'question' => $question->question,
                'selected_answer' => $selectedAnswer ? $selectedAnswer->answer : null,
                'options' => $possibleAnswers,
            ];

            // Prepara datos para el gráfico
            $chartData[] = [
                'question' => $question->question,
                'answers' => $possibleAnswers,
            ];
        }

        return view('admin.surveys.answers.details', compact('answersData', 'chartData'));
    }

    public function showSurveyResults($surveyId)
    {
        // Obtener todas las preguntas de la encuesta
        $questions = Question::where('survey_id', $surveyId)->with('answers')->get();

        $correctAnswers = 0;
        $incorrectAnswers = 0;

        foreach ($questions as $question) {
            foreach ($question->answers as $answer) {
                foreach ($answer->answers as $a) {
                    if ($a['is_correct'] == 1) {
                        $correctAnswers++;
                    } else {
                        $incorrectAnswers++;
                    }
                }
            }
        }

        return view('survey.results', compact('correctAnswers', 'incorrectAnswers'));
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

    public function getTopScores($surveyId, $limit = 20)
    {
        // Realiza la consulta en la tabla survey_scores
        return DB::table('survey_scores')
            ->join('users', 'survey_scores.participant_id', '=', 'users.id') // Une con la tabla users para obtener el nombre del participante
            ->select('users.name', 'survey_scores.score', 'survey_scores.participant_id')
            ->where('survey_scores.survey_id', $surveyId) // Filtra por el ID de la encuesta
            ->orderByDesc('survey_scores.score') // Ordena por puntaje descendente
            ->take($limit) // Limita el número de resultados
            ->get();
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $request->validate(
                [
                    'name' => 'required',
                    'type_survey' => 'required',
                    'description' => 'required',
                    'group_id' => 'required',
                ],
                [
                    'name.required' => 'El nombre es requerido',
                    'type_survey.required' => 'El tipo es requerido',
                    'description.required' => 'Indique el Tipo',
                    'group_id.required' => 'Debe Seleccionar un Grupo de Trabajo',
                ]
            );
        }

        // Crear o actualizar la encuesta
        $survey = Survey::updateOrCreate(
            ['id' => $request->profile_id],
            [
                'type' => $request->type_survey,
                'name' => $request->name,
                'group_id' => $request->group_id,
                'dependency_id' => $request->dependency_id,
                'description' => $request->description,
            ]
        );

        // Crear la tarea
        $taskData = [
            'group_id' => $survey->group_id,
            'details' => $request->description,
            'status' => 0, // Status por defecto
        ];

        $surveyTask = Task::create($taskData);

        // Asociar la relación polimórfica a la tarea
        $surveyTask->typeTasks()->create([
            'typetaskable_id' => $survey->id,
            'typetaskable_type' => Survey::class,
            'status' => 0, // Status inicial de la relación polimórfica
        ]);

        // Obtener los miembros del grupo
        $group = Group::with('members')->findOrFail($survey->group_id);
        $participantIds = $group->members->pluck('id')->toArray();

        // Sincronizar los participantes de la tarea
        $surveyTask->analysts()->sync($request->analyst_id);
        $surveyTask->participants()->sync($participantIds);

        // Sincronizar analistas y participantes de la encuesta
        $survey->analysts()->sync($request->analyst_id);
        $survey->participants()->sync($participantIds);

        return response()->json([
            'success' => 'Encuesta creada satisfactoriamente',
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $survey = Survey::find($id)->delete();

        return response()->json([$survey]);
    }
}
