@extends('errors::minimal')

@section('title', __('Prohibido'))
@section('code', '403')

@section('poem')
    <p>En el rincón restringido de la red,
        donde el acceso queda en suspenso,
        un código 403, el sueño desvanecido,
        esperando el permiso, lo siento.
    </p>
    <p>No puedes pasar, te dice con firmeza,
        este camino está cerrado a tu andar,
        las puertas están selladas, sin certeza,
        de que puedas cruzar, de avanzar.
    </p>
    <p>
        Un error de permisos, un alto en el camino,
        a veces es la norma, una barrera,
        pero no te desanimes, amigo mío,
        hay otras sendas, otros horizontes en la esfera.
    </p>
    <p>
        Quizás el acceso esté reservado para algunos,
        o tal vez un pequeño ajuste sea la solución,
        pero en cada error, un aprendizaje consumimos,
        un recordatorio de la necesaria precaución.
    </p>
    <p>
        Así que en el código 403, en la negación,
        encuentra la oportunidad de crecer,
        y busca el sendero de la adaptación,
        en cada error, un nuevo camino puede florecer.
    </p>
    <small>Poema generado por ChatGPT</small>
    <a href="{{ route('tasks.index') }}" class=""><button class="btn btn-success">Retornar a lo permitido</button></a>

@endsection
