<?php

namespace App\Models\Bioestadistica;

use App\Models\Bioestadistica\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecordValue extends Model
{
    use Auditable;
    protected $table = 'bioestadistica.record_values';

    protected $guarded = ['id'];

    protected $casts = [
        'value_num' => 'decimal:4',
        'value_date' => 'date',
        'value_bool' => 'boolean',
        'value_json' => 'array',
    ];

    public function record(): BelongsTo
    {
        return $this->belongsTo(Record::class);
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class);
    }
}
