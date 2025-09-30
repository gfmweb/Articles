<?php

namespace App\Modules\Articles\Persistence\ORM;

use App\Modules\Users\Persistence\ORM\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Article
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property int $author_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read User $author
 * @property-read \Illuminate\Database\Eloquent\Collection $comments
 */
class Article extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'author_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the author that owns the article.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the comments for the article.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(\App\Modules\Comments\Persistence\ORM\Comment::class);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \App\Modules\Articles\Persistence\Factories\ArticleFactory
     */
    protected static function newFactory()
    {
        return \App\Modules\Articles\Persistence\Factories\ArticleFactory::new();
    }
}
