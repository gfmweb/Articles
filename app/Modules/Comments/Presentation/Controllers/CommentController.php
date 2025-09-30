<?php

namespace App\Modules\Comments\Presentation\Controllers;

use App\Modules\Comments\Application\Actions\CreateCommentAction;
use App\Modules\Comments\Application\Actions\DeleteCommentAction;
use App\Modules\Comments\Application\Actions\UpdateCommentAction;
use App\Modules\Comments\Application\DTOs\CreateCommentDTO;
use App\Modules\Comments\Application\DTOs\UpdateCommentDTO;
use App\Modules\Comments\Application\Queries\GetCommentQuery;
use App\Modules\Comments\Persistence\ORM\Comment;
use App\Modules\Comments\Presentation\Requests\CreateCommentRequest;
use App\Modules\Comments\Presentation\Requests\UpdateCommentRequest;
use App\Modules\Comments\Presentation\Resources\CommentResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class CommentController
{
    use AuthorizesRequests;

    public function __construct(
        private CreateCommentAction $createCommentAction,
        private UpdateCommentAction $updateCommentAction,
        private DeleteCommentAction $deleteCommentAction,
        private GetCommentQuery $getCommentQuery
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

        return CommentResource::collection($comments)
            ->response()
            ->setStatusCode(200);
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
        $comment->load(['author']);

        return (new CommentResource($comment))
            ->additional(['message' => __('comments::messages.created')])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Update the specified comment.
     */
    public function update(UpdateCommentRequest $request, int $id): JsonResponse
    {
        $comment = $this->getCommentQuery->execute($id);
        $this->authorize('update', $comment);

        $dto = UpdateCommentDTO::fromArray($request->validated());
        $updatedComment = $this->updateCommentAction->execute($comment, $dto);
        $updatedComment->load(['author']);

        return (new CommentResource($updatedComment))
            ->additional(['message' => __('comments::messages.updated')])
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(int $id): JsonResponse
    {
        $comment = $this->getCommentQuery->execute($id);
        $this->authorize('delete', $comment);

        $this->deleteCommentAction->execute($comment);

        return response()->json([
            'message' => __('comments::messages.deleted'),
        ]);
    }
}
