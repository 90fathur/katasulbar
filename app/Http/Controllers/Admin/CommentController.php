<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->get('status', 'all');

        $query = Comment::with('article');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $comments = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $counts = [
            'all' => Comment::count(),
            'pending' => Comment::where('status', 'pending')->count(),
            'approved' => Comment::where('status', 'approved')->count(),
            'rejected' => Comment::where('status', 'rejected')->count(),
            'spam' => Comment::where('status', 'spam')->count(),
        ];

        return view('admin.comments.index', compact('comments', 'status', 'counts'));
    }

    public function updateStatus(Request $request, Comment $comment, string $status): RedirectResponse
    {
        if (! in_array($status, ['approved', 'rejected', 'spam', 'pending'])) {
            return back()->with('error', 'Status tidak valid.');
        }

        $comment->update(['status' => $status]);

        ActivityLog::log('Moderasi Komentar', "Mengubah status komentar #{$comment->id} menjadi {$status}");

        return back()->with('success', "Status komentar berhasil diubah menjadi {$status}.");
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $comment->delete();
        ActivityLog::log('Hapus Komentar', "Menghapus komentar #{$comment->id}");

        return back()->with('success', 'Komentar berhasil dihapus.');
    }
}
