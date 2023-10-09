<?php

namespace App\Admin\Globales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use App\Models\User;


class Group extends Model
{
    use HasFactory;
    use NodeTrait;

    protected $table = 'groups';

    protected $fillable = ['name', 'members'];

    public function members()
    {
        return $this->belongsToMany(User::class, 'groups_has_members', 'group_id', 'user_id');
    }
}
