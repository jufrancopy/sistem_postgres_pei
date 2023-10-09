<?php

namespace App\Admin\Planificacion\Foda;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use App\Models\User;

class FodaGroup extends Model
{
    use HasFactory;
    use NodeTrait;

    protected $table = 'planificacion.foda_groups';

    protected $fillable = ['name', 'members'];

    public function members()
    {
        return $this->belongsToMany(User::class, 'planificacion.foda_groups_has_members', 'group_id', 'user_id');
    }
}
