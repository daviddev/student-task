<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $full_name
 * @property string $email
 * @property string $gender
 * @property string $phone_number
 * @property array $availability
 * @property Carbon $birthdate
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection<TargetData> $targetData
 */
class Student extends Model
{
    use HasFactory, Notifiable;

    const GENDER_MALE = 'male';
    const GENDER_FEMALE = 'female';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'gender',
        'phone_number',
        'birthdate',
        'availability',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'full_name',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'birthdate' => 'date',
            'availability' => 'array',
        ];
    }

    /**
     * Get the student's full_name attribute.
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn() => "{$this->first_name} {$this->middle_name} {$this->last_name}",
        );
    }

    /**
     * Get students gender types.
     *
     * @return string[]
     */
    public static function genderTypes(): array
    {
        return [
            self::GENDER_MALE,
            self::GENDER_FEMALE,
        ];
    }

    /**
     * TargetData model relation.
     *
     * @return HasMany
     */
    public function targetData(): HasMany
    {
        return $this->hasMany(TargetData::class);
    }
}
