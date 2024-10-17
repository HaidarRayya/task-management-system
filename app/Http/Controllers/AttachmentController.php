<?php

namespace App\Http\Controllers;

use App\Enums\UserPermission;
use App\Http\Requests\Attachment\StoreAttachmentRequest;
use App\Http\Requests\Attachment\UpdateAttachmentRequest;
use App\Models\Attachment;
use App\Models\Task;
use App\Services\AttachmentService;
use App\Services\AuthService;

class AttachmentController extends Controller
{
    protected $attachmentService;
    public function __construct(AttachmentService $attachmentService)
    {
        $this->attachmentService = $attachmentService;
        $this->authorizeResource('Attachment');
    }
    /**
     * Display a listing of the resource.
     */

    /**
     * get all  comments
     * 
     * @param Task $task 
     *
     * @return response  of the status of operation : permissions 
     */
    public function index(int $task_id)
    {
        AuthService::canDo(UserPermission::GET_ATTACHMENT->value);
        $attachments = $this->attachmentService->allAttachments($task_id);
        return response()->json([
            'status' => 'success',
            'data' => [
                'attachments' => $attachments
            ]
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAttachmentRequest $request, int $task_id)
    {
        AuthService::canDo(UserPermission::CREATE_ATTACHMENT->value);
        $attachmentData = $request->file('file');
        $attachment = $this->attachmentService->createAttachment($attachmentData, $task_id);
        return response()->json([
            'status' => 'success',
            'data' => [
                'attachment' => $attachment
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $task_id, $attachment_id)
    {
        AuthService::canDo(UserPermission::GET_ATTACHMENT->value);

        $attachment = $this->attachmentService->oneAttachment($attachment_id);
        return response()->json([
            'status' => 'success',
            'data' => [
                'attachment' => $attachment
            ]
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttachmentRequest $request, int  $task_id,  int $attachment_id)
    {
        AuthService::canDo(UserPermission::UPDATE_ATTACHMENT->value);

        $attachmentData = $request->file('file');
        $attachment = $this->attachmentService->updateAttachment($attachmentData, $attachment_id);
        return response()->json([
            'status' => 'success',
            'data' => [
                'attachment' => $attachment
            ]
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $task_id, int  $attachment_id)
    {
        AuthService::canDo(UserPermission::DELETE_ATTACHMENT->value);

        $this->attachmentService->deleteAttachment($attachment_id);
        return response()->json(status: 204);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function download(int $task_id, int  $attachment_id)
    {
        AuthService::canDo(UserPermission::DOWNLOAD_ATTACHMENT->value);
        $file = $this->attachmentService->downloadAttachment($attachment_id);
        return $file;
    }
}