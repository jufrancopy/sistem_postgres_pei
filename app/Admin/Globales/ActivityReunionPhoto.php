<?php

namespace App\Admin\Globales;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ActivityReunionPhoto extends Model
{
    protected $table = 'activity_reunion_photos';

    protected $fillable = [
        'activity_task_id',
        'filename',
        'thumb_path',
        'original_name',
        'size_bytes',
        'uploaded_by',
    ];

    public function task()
    {
        return $this->belongsTo(ActivityTask::class, 'activity_task_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * URL pública de la imagen full-size.
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->filename);
    }

    /**
     * URL pública de la miniatura.
     */
    public function getThumbUrlAttribute(): string
    {
        return asset('storage/' . $this->thumb_path);
    }

    /**
     * Tamaño legible (KB / MB).
     */
    public function getSizeHumanAttribute(): string
    {
        $kb = round($this->size_bytes / 1024, 1);
        return $kb < 1024 ? "{$kb} KB" : round($kb / 1024, 2) . ' MB';
    }
}
