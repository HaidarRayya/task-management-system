<?php

namespace App\Http\Controllers;

use App\Enums\UserPermission;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Task;
use App\Services\AuthService;
use App\Services\CommentService;

class CommentController extends Controller
{
    protected $commentService;
    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
        $this->authorizeResource('Comment');
    }
    /**
     * Display a listing of the resource.
     */

    /**
     * get all  comments
     * 
     * @param int $task_id 
     *
     * @return response  of the status of operation : permissions 
     */
    public function index($task_id)
    {
        AuthService::canDo(UserPermission::GET_COMMENT->value);

        $comments = $this->commentService->allComments($task_id);
        return response()->json([
            'status' => 'success',
            'data' => [
                'comments' => $comments
            ]
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request, int $task_id)
    {
        AuthService::canDo(UserPermission::CREATE_COMMENT->value);

        $commentData = $request->validated();
        $comment = $this->commentService->createComment($commentData, $task_id);
        return response()->json([
            'status' => 'success',
            'data' => [
                'comment' => $comment
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($task_id,  $comment_id)
    {
        AuthService::canDo(UserPermission::GET_COMMENT->value);

        $comment = $this->commentService->oneComment($comment_id);
        return response()->json([
            'status' => 'success',
            'data' => [
                'comment' => $comment
            ]
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, $task_id,  $comment_id)
    {
        AuthService::canDo(UserPermission::UPDATE_COMMENT->value);

        $commentData = $request->validated();

        $comment = $this->commentService->updateComment($commentData, $comment_id);
        return response()->json([
            'status' => 'success',
            'data' => [
                'comment' => $comment
            ]
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($task_id, $comment_id)
    {
        AuthService::canDo(UserPermission::DELETE_COMMENT->value);

        $this->commentService->deleteComment($comment_id);
        return response()->json(status: 204);
    }
}