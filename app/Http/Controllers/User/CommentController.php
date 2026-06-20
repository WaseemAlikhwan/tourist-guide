<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Models\Activity;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(CommentRequest $request, Activity $activity)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['activity_id'] = $activity->id;
        $data['is_approved'] = false; // يحتاج موافقة من الأدمن

        Comment::create($data);

        return back()->with('success', 'تم إضافة التعليق. سيظهر بعد اعتماده من الإدارة.');
    }

    public function update(CommentRequest $request, Comment $comment)
    {
        $this->authorizeOwner($comment);

        $comment->update($request->validated());

        return back()->with('success', 'تم تحديث التعليق بنجاح');
    }

    public function destroy(Comment $comment)
    {
        $this->authorizeOwner($comment);

        $comment->delete();

        return back()->with('success', 'تم حذف التعليق');
    }

    private function authorizeOwner(Comment $comment): void
    {
        abort_unless(Auth::id() === $comment->user_id, 403);
    }
}
