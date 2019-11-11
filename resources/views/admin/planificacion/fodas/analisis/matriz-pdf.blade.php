<!DOCTYPE html>
<html>

<head>
    <title>PDF - Cruce de Ambientes</title>
    <style type="text/css">
    th,
    td {
        border: solid 1px #777;
        padding: 2px;
        margin: 2px;
    }
    </style>
</head>

<body>
    <img src="https://i0.wp.com/logoroga.com/wp-content/uploads/2016/08/IPSlogo.png?fit=800%2C800">
    <table>
        <thead>
            <tr>
                <th>
                    <h3>ANALISIS INTERNO</h3>
                </th>
                <th>
                    <h3>ANALISIS EXTERNO</h3>
                </th>
            </tr>
            <tr>
                <th>Debilidad
                @foreach($debilidades as $v)        
                <ul>
                    <li>{{$v->aspecto->nombre}}</li>
                </ul>
                @endforeach
                </th>
                
                <th>Amenaza
                @foreach($amenazas as $v)        
                <ul>
                    <li>{{$v->aspecto->nombre}}</li>
                </ul>
                @endforeach
                </th>
            </tr>
            <tr>
                <th>Fortaleza
                    @foreach($fortalezas as $v)        
                    <ul>
                        <li>{{$v->aspecto->nombre}}</li>
                    </ul>
                    @endforeach
                </th>
                <th>Oportunidad
                @foreach($oportunidades as $v)        
                <ul>
                    <li>{{$v->aspecto->nombre}}</li>
                </ul>
                @endforeach
                </th>
            </tr>
        </thead>
        <tbody>

    </table>
</body>

</html>