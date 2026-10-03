<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use App\Models\NewsletterSubscriber;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InteractionController extends Controller
{
    public function storeComment(Request $request, int $articleId): RedirectResponse
    {
        $article = Article::published()->findOrFail($articleId);

        if (! $article->allow_comments) {
            return back()->with('error', 'Komentar untuk artikel ini telah ditutup.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'website' => 'nullable|url|max:200',
            'content' => 'required|string|min:5|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
            'captcha_answer' => 'required|numeric',
            'captcha_expected' => 'required|numeric',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'content.required' => 'Isi komentar tidak boleh kosong.',
            'content.min' => 'Komentar minimal 5 karakter.',
        ]);

        // Simple anti-spam math verification
        if ((int) $request->input('captcha_answer') !== (int) $request->input('captcha_expected')) {
            return back()->withInput()->with('error', 'Hasil penjumlahan verifikasi salah. Silakan coba kembali.');
        }

        $autoApprove = Setting::get('auto_approve_comments', '0') === '1';

        Comment::create([
            'article_id' => $article->id,
            'parent_id' => $validated['parent_id'] ?? null,
            'name' => strip_tags($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'website' => $validated['website'] ?? null,
            'content' => strip_tags($validated['content']),
            'status' => $autoApprove ? 'approved' : 'pending',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $message = $autoApprove
            ? 'Komentar Anda berhasil dipublikasikan.'
            : 'Terima kasih! Komentar Anda sedang menunggu moderasi oleh tim redaksi.';

        return back()->with('comment_success', $message);
    }

    public function subscribeNewsletter(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email|max:150',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $email = strtolower(trim($validated['email']));

        $subscriber = NewsletterSubscriber::where('email', $email)->first();

        if ($subscriber) {
            if (! $subscriber->is_active) {
                $subscriber->update(['is_active' => true]);

                return response()->json([
                    'success' => true,
                    'message' => 'Langganan Anda telah diaktifkan kembali. Terima kasih!',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Email Anda sudah terdaftar dalam newsletter Halo Sulbar.',
            ]);
        }

        NewsletterSubscriber::create([
            'email' => $email,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih telah berlangganan! Berita terkini akan dikirimkan ke email Anda.',
        ]);
    }

    public function bookmarks(Request $request): View
    {
        return view('frontend.bookmarks');
    }
}
