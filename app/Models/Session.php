<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $student_id
 * @property int $duration
 * @property int $rate
 * @property string $type
 * @property bool $is_notified
 * @property bool $can_rate
 * @property Carbon $start_time
 * @property Carbon $end_time
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Student $student
 */
class Session extends Model
{
    use HasFactory;

    const TYPE_ONE_TIME = 'one-time';
    const TYPE_REPEATED = 'repeated';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'start_time',
        'end_time',
        'type',
        'rate',
        'is_notified',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'is_notified' => 'boolean',
        ];
    }

    /**
     * Get the session's can_rate attribute.
     */
    protected function canRate(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->end_time < now(),
        );
    }

    /**
     * Get the session's duration attribute.
     */
    protected function duration(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->start_time->diffInMinutes($this->end_time),
        );
    }

    /**
     * Get students gender types.
     *
     * @return string[]
     */
    public static function sessionTypes(): array
    {
        return [
            self::TYPE_ONE_TIME,
            self::TYPE_REPEATED,
        ];
    }

    /**
     * Student model relation.
     *
     * @return BelongsTo
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
