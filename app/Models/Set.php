<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Set extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'year_level',
        'set_code',
        'student_count',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function getDisplayNameAttribute(): string
    {
        $yearLabel = match ($this->year_level) {
            1 => '1st Year',
            2 => '2nd Year',
            3 => '3rd Year',
            4 => '4th Year',
            default => $this->year_level . ' Year',
        };

        $setLabel = $this->set_code ? $this->set_code : 'No Set';

        return $this->course->name . ' - ' . $yearLabel . ' - ' . $setLabel;
    }
}
