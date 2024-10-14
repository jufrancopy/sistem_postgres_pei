{{-- resources/views/admin/surveys/partials/questions.blade.php --}}
<div class="accordion" id="accordionExample">
    @foreach ($survey->questions as $key => $question)
        <div class="card">
            <div class="card-header d-flex justify-content-between" id="heading{{ $key }}">
                <h2 class="mb-0">
                    <button class="btn btn-link text-left" type="button" data-toggle="collapse"
                        data-target="#collapse{{ $key }}" aria-expanded="true"
                        aria-controls="collapse{{ $key }}">
                        {!! $question->question !!}
                    </button>
                </h2>
                <button class="btn btn-danger btn-circle delete-question" data-id="{{ $question->id }}">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                </button>
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
