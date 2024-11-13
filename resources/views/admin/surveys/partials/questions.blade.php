{{-- Inicio Lista de Preguntas --}}
<div class="card-body">
    <div class="accordion" id="accordionExample">

        @foreach ($survey->questions as $key => $question)
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="mb-0" style="flex-grow: 1;">
                        <button class="btn btn-link text-left" type="button" data-toggle="collapse"
                            data-target="#collapse{{ $key }}" aria-expanded="true"
                            aria-controls="collapse{{ $key }}"
                            style="white-space: normal; overflow-wrap: break-word;">
                            {!! $question->question !!}
                        </button>
                    </h2>

                    <div class="ml-2 d-flex align-items-center">
                        @if ($question->countAnswers() > 0)
                            <button class="btn btn-success btn-circle mr-2">
                                <i class="fa fa-check" aria-hidden="true"></i>
                            </button>
                        @else
                            <button class="btn btn-warning btn-circle mr-2">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </button>
                        @endif

                        <button class="btn btn-danger btn-circle delete-question" data-id="{{ $question->id }}">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>

                <div id="collapse{{ $key }}" class="collapse" aria-labelledby="heading{{ $key }}"
                    data-parent="#accordionExample">
                    <div class="card-body">
                        <ul>
                            @php
                                $answersArray = $question->answersHasQuestions;
                            @endphp

                            @if ($answersArray->isNotEmpty())
                                @foreach ($answersArray as $ans)
                                    @php
                                        $decodedAnswers = is_array($ans->answers)
                                            ? $ans->answers
                                            : json_decode($ans->answers, true);
                                    @endphp

                                    @if (!empty($decodedAnswers))
                                        @foreach ($decodedAnswers as $item)
                                            <li @if (isset($item['is_correct']) && $item['is_correct']) style="color: green;" @endif>
                                                {{ is_string($item['answer']) ? $item['answer'] : json_encode($item['answer']) }}
                                                @if (isset($item['is_correct']) && $item['is_correct'])
                                                    (Correcta)
                                                @endif
                                            </li>
                                        @endforeach
                                    @else
                                        <li>No hay respuestas disponibles para esta pregunta.</li>
                                    @endif
                                @endforeach
                            @else
                                <li>No hay respuestas disponibles para esta pregunta.</li>
                            @endif
                        </ul>
                    </div>

                </div>
            </div>
        @endforeach

    </div>
</div>
{{-- Fin Lista de Preguntas --}}
