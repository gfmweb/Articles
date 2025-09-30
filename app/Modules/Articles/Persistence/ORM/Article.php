<?php

namespace App\Modules\Articles\Persistence\ORM;

use App\Modules\Articles\Persistence\Factories\ArticleFactory;
use App\Modules\Users\Persistence\ORM\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property string $content
 * @property int $author_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User $author
 * @property-read Collection $comments
 * @property-read int $comments_count
 * @property-read bool $is_author
 * @property-read bool $has_commented
 */
class Article extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'author_id',
    ];

    /**
     * @var array<int, string>
     */
    protected $appends = [
        'is_author',
        'has_commented',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'is_author' => 'boolean',
            'has_commented' => 'boolean',
        ];
    }

    public function getIsAuthorAttribute(): bool
    {
        return (bool) ($this->attributes['is_author'] ?? false);
    }

    public function getHasCommentedAttribute(): bool
    {
        return (bool) ($this->attributes['has_commented'] ?? false);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(\App\Modules\Comments\Persistence\ORM\Comment::class);
    }

    protected static function newFactory(): ArticleFactory
    {
        return ArticleFactory::new();
    }
}
