<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NewsletterController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $query = NewsletterSubscriber::query();

        if ($search) {
            $query->where('email', 'like', "%{$search}%");
        }

        $subscribers = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('admin.newsletters.index', compact('subscribers', 'search'));
    }

    public function destroy(NewsletterSubscriber $subscriber): RedirectResponse
    {
        $email = $subscriber->email;
        $subscriber->delete();

        ActivityLog::log('Hapus Subscriber', "Menghapus subscriber '{$email}'");

        return back()->with('success', 'Subscriber berhasil dihapus.');
    }

    public function export(): StreamedResponse
    {
        $subscribers = NewsletterSubscriber::where('is_active', true)->get();

        $response = new StreamedResponse(function () use ($subscribers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Email', 'Tanggal Berlangganan']);

            foreach ($subscribers as $row) {
                fputcsv($handle, [$row->id, $row->email, $row->created_at->format('Y-m-d H:i:s')]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="newsletter_subscribers_halosulbar.csv"');

        return $response;
    }
}
