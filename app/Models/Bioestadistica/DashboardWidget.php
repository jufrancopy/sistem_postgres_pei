<?php

namespace App\Models\Bioestadistica;

use App\Models\Bioestadistica\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DashboardWidget extends Model
{
    use Auditable;
    protected $table = 'bioestadistica.dashboard_widgets';

    protected $guarded = ['id'];

    protected $casts = [
        'query_config' => 'array',
    ];

    public function dashboard(): BelongsTo
    {
        return $this->belongsTo(Dashboard::class);
    }
}
