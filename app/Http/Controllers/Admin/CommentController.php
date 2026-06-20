<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::with(['user', 'activity'])
            ->latest()
            ->paginate(20);

        return view('admin.comments.index', compact('comments'));
    }

    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
            'is_approved' => 'sometimes|boolean',
        ]);

        $data = $request->only(['comment']);
        $data['is_approved'] = true; // عند الحفظ من الأدمن، يتم اعتماده تلقائياً

        $comment->update($data);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'تم حفظ واعتماد التعليق بنجاح']);
        }

        return back()->with('success', 'تم حفظ واعتماد التعليق بنجاح');
    }

    public function toggleApproval(Comment $comment)
    {
        $comment->update(['is_approved' => !$comment->is_approved]);

        $message = $comment->is_approved ? 'تم اعتماد التعليق' : 'تم إلغاء اعتماد التعليق';

        return back()->with('success', $message);
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        return back()->with('success', 'تم حذف التعليق');
    }
}
