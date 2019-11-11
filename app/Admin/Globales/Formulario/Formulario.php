<?php

namespace App\Admin\Globales\Formulario;

use Illuminate\Database\Eloquent\Model;

class Formulario extends Model
{
    protected $table = 'globales.formulario_formularios';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $fillable = ['item', 'user_id'];
    
}
