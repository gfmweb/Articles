<?php

namespace App\Modules\Articles\Presentation\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Modules\Articles\Persistence\ORM\Article
 */
class ArticleResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'author_id' => $this->author_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'author' => $this->whenLoaded('author'),
            'comments' => $this->whenLoaded('comments'),
            'comments_count' => $this->when(isset($this->comments_count), $this->comments_count),
            'is_author' => $this->when(isset($this->is_author), $this->is_author),
            'has_commented' => $this->when(isset($this->has_commented), $this->has_commented),
        ];
    }
}
