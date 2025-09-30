<?php

namespace App\Modules\Users\Persistence\ORM;

use App\Modules\Users\Persistence\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
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
     * Get the articles for the user.
     * Note: This creates a coupling between modules. Consider using events/listeners
     * or a shared contract if strict module isolation is required.
     */
    public function articles(): HasMany
    {
        return $this->hasMany('App\Modules\Articles\Persistence\ORM\Article', 'author_id');
    }

    /**
     * Get the comments for the user.
     * Note: This creates a coupling between modules. Consider using events/listeners
     * or a shared contract if strict module isolation is required.
     */
    public function comments(): HasMany
    {
        return $this->hasMany('App\Modules\Comments\Persistence\ORM\Comment', 'author_id');
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
