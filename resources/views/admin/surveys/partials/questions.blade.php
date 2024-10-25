{{-- resources/views/admin/surveys/partials/questions.blade.php --}}
<div class="accordion" id="accordionExample">
    @foreach ($survey->questions as $key => $question)
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center" id="heading{{ $key }}">
                <h2 class="mb-0">
                    <button class="btn btn-link text-left" type="button" data-toggle="collapse"
                        data-target="#collapse{{ $key }}" aria-expanded="true"
                        aria-controls="collapse{{ $key }}">
                        {!! $question->question !!}
                    </button>
                </h2>

                <div class="button-group d-flex">
                    {{-- Mostrar el conteo de respuestas para depurar --}}
                    @php
                        $answerCount = $question->answers()->count();
                    @endphp
                    <span>Conteo de respuestas: {{ $answerCount }}</span> {{-- Muestra el conteo para verificarlo --}}
                    
                    <button class="btn btn-circle {{ $answerCount > 0 ? 'btn-success' : 'btn-danger' }} ml-2">
                        <i class="fa {{ $answerCount > 0 ? 'fa-check' : 'fa-times-circle-o' }}" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <div id="collapse{{ $key }}" class="collapse" aria-labelledby="heading{{ $key }}"
                data-parent="#accordionExample">
                <div class="card-body">
                    <ul>
                        @php
                            $firstAnswerRecord = $question->answers()->first();
                            $answersArray = $firstAnswerRecord ? json_decode($firstAnswerRecord->answers, true) : [];
                        @endphp
                        @if (!empty($answersArray))
                            @foreach ($answersArray as $ans)
                                <li @if (isset($ans['is_correct']) && $ans['is_correct']) style="color: green;" @endif>
                                    {{ $ans['answer'] }}
                                    @if (isset($ans['is_correct']) && $ans['is_correct'])
                                        (Correcta)
                                    @endif
                                </li>
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
