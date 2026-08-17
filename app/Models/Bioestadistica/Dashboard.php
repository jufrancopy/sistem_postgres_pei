<?php

namespace App\Models\Bioestadistica;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dashboard extends BioestadisticaModel
{
    protected $table = 'bioestadistica.dashboards';

    protected $casts = [
        'es_default' => 'boolean',
    ];

    public function widgets(): HasMany
    {
        return $this->hasMany(DashboardWidget::class)
            ->orderBy('pos_y')
            ->orderBy('pos_x');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isInstitutional(): bool
    {
        return $this->user_id === null;
    }
}
