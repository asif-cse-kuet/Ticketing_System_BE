<?php

namespace App\Modules\Comments\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Modules\Comments\Requests\StoreCommentRequest;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request)
    {
        $user = $request->user();

        // Map string to actual model class (you can expand this later)
        $typeMap = [
            'ticket' => Ticket::class,
        ];

        $modelClass = $typeMap[$request->commentable_type];

        $commentable = $modelClass::findOrFail($request->commentable_id);

        $comment = $commentable->comments()->create([
            'user_id' => $user->id,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'message' => 'Comment added successfully',
            'comment' => $comment->load('user'),
        ], 201);
    }
}
