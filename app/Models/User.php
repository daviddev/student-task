<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $report_template
 * @property string $password
 * @property string $remember_token
 * @property Carbon $email_verified_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'report_template',
        'password',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Prepare template.
     *
     * @return string
     */
    public function prepareTemplate(): string
    {
        $shortCodes = [
            '@student_full_name',
            '@session_date',
            '@session_minutes',
            '@session_start_time',
            '@session_end_time',
            '@target_start_date',
            '@target_end_date',
            '@target',
            '@session_rating',
        ];
        $template = $this->report_template;

        foreach ($shortCodes as $shortCode) {
            $variable = str_replace('@', '$', $shortCode);
            $template = str_replace($shortCode, "{{ $variable }}", $template);
        }

        return $template;
    }
}
