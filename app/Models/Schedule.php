<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'set_id',
        'faculty_id',
        'room_id',
        'day',
        'start_time',
        'end_time',
        'class_type',
        'g_code',
        'color',
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function set(): BelongsTo
    {
        return $this->belongsTo(Set::class);
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function timetableTextColor(): string
    {
        $rgb = $this->hexToRgb($this->color);

        if (!$rgb) {
            return '#0b1220';
        }

        $yiq = (($rgb['r'] * 299) + ($rgb['g'] * 587) + ($rgb['b'] * 114)) / 1000;

        return $yiq >= 150 ? '#0b1220' : '#ffffff';
    }

    public function timetableBorderColor(): string
    {
        $rgb = $this->hexToRgb($this->color);

        if (!$rgb) {
            return '#7aa43a';
        }

        $yiq = (($rgb['r'] * 299) + ($rgb['g'] * 587) + ($rgb['b'] * 114)) / 1000;

        if ($yiq < 150) {
            return '#ffffff';
        }

        return sprintf(
            '#%02x%02x%02x',
            max(0, (int) round($rgb['r'] * 0.65)),
            max(0, (int) round($rgb['g'] * 0.65)),
            max(0, (int) round($rgb['b'] * 0.65)),
        );
    }

    private function hexToRgb(?string $hex): ?array
    {
        if (!is_string($hex) || !preg_match('/^#[0-9A-Fa-f]{6}$/', $hex)) {
            return null;
        }

        return [
            'r' => hexdec(substr($hex, 1, 2)),
            'g' => hexdec(substr($hex, 3, 2)),
            'b' => hexdec(substr($hex, 5, 2)),
        ];
    }
}
