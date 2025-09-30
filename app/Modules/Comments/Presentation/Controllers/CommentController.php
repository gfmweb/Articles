<?php

namespace App\Modules\Comments\Presentation\Controllers;

use App\Modules\Comments\Application\Actions\CreateCommentAction;
use App\Modules\Comments\Application\Actions\DeleteCommentAction;
use App\Modules\Comments\Application\Actions\UpdateCommentAction;
use App\Modules\Comments\Application\DTOs\CreateCommentDTO;
use App\Modules\Comments\Application\DTOs\UpdateCommentDTO;
use App\Modules\Comments\Presentation\Requests\CreateCommentRequest;
use App\Modules\Comments\Presentation\Requests\UpdateCommentRequest;
use App\Modules\Comments\Persistence\ORM\Comment;
use Illuminate\Http\JsonResponse;

class CommentController
{
    public function __construct(
        private CreateCommentAction $createCommentAction,
        private UpdateCommentAction $updateCommentAction,
        private DeleteCommentAction $deleteCommentAction
    ) {}

    /**
     * Get comments for a specific article.
     */
    public function index(int $articleId): JsonResponse
    {
        $comments = Comment::with('author')
            ->where('article_id', $articleId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'data' => $comments,
        ]);
    }

    /**
     * Store a newly created comment.
     */
    public function store(CreateCommentRequest $request, int $articleId): JsonResponse
    {
        $data = $request->validated();
        $data['article_id'] = $articleId;
        $data['author_id'] = $request->user()->id;

        $dto = CreateCommentDTO::fromArray($data);
        $comment = $this->createCommentAction->execute($dto);

        return response()->json([
            'data' => $comment->load(['author']),
            'message' => __('comments::messages.created'),
        ], 201);
    }

    /**
     * Update the specified comment.
     */
    public function update(UpdateCommentRequest $request, int $id): JsonResponse
    {
        $comment = Comment::findOrFail($id);

        // Проверяем, что пользователь является автором комментария
        if ($request->user()->id !== $comment->author_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $dto = UpdateCommentDTO::fromArray($request->validated());
        $updatedComment = $this->updateCommentAction->execute($comment, $dto);

        return response()->json([
            'data' => $updatedComment->load(['author']),
            'message' => __('comments::messages.updated'),
        ]);
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(int $id): JsonResponse
    {
        $comment = Comment::findOrFail($id);

        // Проверяем, что пользователь является автором комментария
        if (request()->user()->id !== $comment->author_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $this->deleteCommentAction->execute($comment);

        return response()->json([
            'message' => __('comments::messages.deleted'),
        ]);
    }
}
