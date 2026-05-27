<?php

return [
    /*
     |--------------------------------------------------------------------------
     | Umbral de la Matriz FODA
     |--------------------------------------------------------------------------
     | Valor mínimo de (ocurrencia × impacto) para que un aspecto
     | aparezca en la Matriz FODA y el Cruce de Ambientes.
     |
     | Referencia de valores posibles:
     |   0.03 = Media × Bajo       (muy permisivo)
     |   0.09 = Media × Moderado   (recomendado MECIP)
     |   0.17 = Muy Alta × Bajo    (valor actual — restrictivo)
     |   0.20 = Alta × Alto        (estricto)
     |
     | Para cambiar: editá este número y ejecutá: php artisan config:clear
     |
     */
    'umbral_matriz' => 0.09,
];
