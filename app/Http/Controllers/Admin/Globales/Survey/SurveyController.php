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
            $data = Survey::with(['analysts', 'group', 'questions'])->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-primary btn-circle editSurvey" title="Editar"><i class="far fa-edit"></i></a>';
                    $btn .= ' <a href="' . route('surveys.show', $row->id) . '" class="btn btn-warning btn-circle" title="Gestionar preguntas"><i class="fa fa-list-ol"></i></a>';
                    $btn .= ' <a href="' . route('surveys.show.details', $row->id) . '" class="btn btn-info btn-circle" title="Ver detalles"><i class="fa fa-chart-bar"></i></a>';
                    $btn .= ' <a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-danger btn-circle deleteSurvey" title="Eliminar"><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->addColumn('analysts', fn(Survey $s) => $s->analysts->pluck('name')->implode(', '))
                ->addColumn('group',    fn(Survey $s) => $s->group?->name ?? '—')
                ->addColumn('preguntas', fn(Survey $s) => $s->questions->count())
                ->addColumn('participantes', fn(Survey $s) => $s->participants()->count())
                ->addColumn('completados', fn(Survey $s) => $s->participants()->wherePivot('completed', true)->count())
                ->rawColumns(['action'])
                ->make(true);
        }

        // KPIs para el dashboard
        $totalEncuestas    = Survey::count();
        $totalPreguntas    = \App\Models\Admin\Globales\Question::count();
        $totalParticipantes = DB::table('participants_has_surveys')->distinct('participant_id')->count();
        $totalCompletadas  = DB::table('participants_has_surveys')->where('completed', true)->count();
        $encuestasRecientes = Survey::with(['questions', 'analysts', 'group'])
            ->latest()->limit(5)->get();

        return view('admin.surveys.index', compact(
            'totalEncuestas', 'totalPreguntas', 'totalParticipantes',
            'totalCompletadas', 'encuestasRecientes'
        ));
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
        $survey    = Survey::with(['questions.answersHasQuestions', 'group', 'analysts'])->findOrFail($surveyID);
        $questions = $survey->questions;

        // Total participantes y completados
        $totalParticipantes = $survey->participants()->count();
        $totalCompletados   = $survey->participants()->wherePivot('completed', true)->count();
        $pctCompletado      = $totalParticipantes > 0
            ? round($totalCompletados / $totalParticipantes * 100) : 0;

        // Para cada pregunta: cuántas veces fue seleccionada cada opción
        $resultados = [];
        foreach ($questions as $question) {
            $answersRow = DB::table('answers_has_questions')
                ->where('question_id', $question->id)->first();
            $opciones = $answersRow ? json_decode($answersRow->answers, true) : [];

            // Contar respuestas por opción
            $conteos = [];
            $totalRespuestas = 0;
            foreach ($opciones as $opcion) {
                $texto = is_array($opcion['answer']) ? json_encode($opcion['answer']) : $opcion['answer'];
                // PostgreSQL: la columna answer es JSON, comparar con cast o usando ::text
                $count = DB::table('answers')
                    ->where('question_id', $question->id)
                    ->whereRaw('answer::text = ?', [json_encode($texto)])
                    ->count();
                $conteos[] = [
                    'answer'     => $texto,
                    'is_correct' => $opcion['is_correct'] ?? false,
                    'count'      => $count,
                ];
                $totalRespuestas += $count;
            }

            $resultados[] = [
                'question'        => $question->question,
                'opciones'        => $conteos,
                'total_respuestas'=> $totalRespuestas,
            ];
        }

        // Mi respuesta (si el usuario logueado respondió)
        $miRespuesta = [];
        foreach ($questions as $question) {
            $ans = DB::table('answers')
                ->where('question_id', $question->id)
                ->where('participant_id', auth()->id())
                ->value(DB::raw('answer::text'));
            $miRespuesta[$question->id] = $ans ? json_decode($ans, true) : null;
        }

        // Datos para el gráfico de participación
        $chartData = $resultados;

        // Mantener compatibilidad con la variable $answersData que usa la vista original
        $answersData = collect($resultados)->map(function($r) use ($miRespuesta, $questions) {
            $q = $questions->firstWhere('question', $r['question']);
            return [
                'question'        => $r['question'],
                'selected_answer' => $q ? ($miRespuesta[$q->id] ?? null) : null,
                'options'         => collect($r['opciones'])->map(fn($o) => [
                    'answer'     => $o['answer'],
                    'is_correct' => $o['is_correct'],
                    'count'      => $o['count'],
                ])->toArray(),
                'total_respuestas'=> $r['total_respuestas'],
            ];
        })->toArray();

        return view('admin.surveys.answers.details', compact(
            'survey', 'answersData', 'chartData',
            'totalParticipantes', 'totalCompletados', 'pctCompletado', 'miRespuesta'
        ));
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
