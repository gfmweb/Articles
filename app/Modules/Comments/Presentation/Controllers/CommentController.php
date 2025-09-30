<?php

namespace App\Modules\Comments\Presentation\Controllers;

use App\Modules\Comments\Application\Actions\CreateCommentAction;
use App\Modules\Comments\Application\Actions\DeleteCommentAction;
use App\Modules\Comments\Application\DTOs\CreateCommentDTO;
use App\Modules\Comments\Presentation\Requests\CreateCommentRequest;
use Illuminate\Http\JsonResponse;

class CommentController
{
    public function __construct(
        private CreateCommentAction $createCommentAction,
        private DeleteCommentAction $deleteCommentAction
    ) {}

    /**
     * Store a newly created comment.
     */
    public function store(CreateCommentRequest $request, int $articleId): JsonResponse
    {
        $data = $request->validated();
        $data['article_id'] = $articleId;

        $dto = CreateCommentDTO::fromArray($data);
        $comment = $this->createCommentAction->execute($dto);

        return response()->json([
            'data' => $comment->load(['author']),
            'message' => __('comments::messages.created'),
        ], 201);
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(int $id): JsonResponse
    {
        // Комментарий уже получен и проверен в middleware
        $comment = request()->get('comment');

        $this->deleteCommentAction->execute($comment);

        return response()->json([
            'message' => __('comments::messages.deleted'),
        ]);
    }
}
