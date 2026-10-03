<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $type = $request->get('type');

        $query = Media::with('uploader');

        if ($search) {
            $query->where('name', 'like', "%{$search}%")->orWhere('file_name', 'like', "%{$search}%");
        }

        if ($type === 'image') {
            $query->where('mime_type', 'like', 'image/%');
        } elseif ($type === 'video') {
            $query->where('mime_type', 'like', 'video/%');
        } elseif ($type === 'document') {
            $query->where('mime_type', 'application/pdf');
        }

        $mediaItems = $query->orderByDesc('created_at')->paginate(24)->withQueryString();

        return view('admin.media.index', compact('mediaItems', 'search', 'type'));
    }

    public function upload(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'files.*' => 'required|file|mimes:jpg,jpeg,png,webp,gif,mp4,webm,pdf|max:10240',
        ]);

        $uploaded = [];

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $mime = $file->getMimeType();
                $size = $file->getSize();
                $path = $file->store('media', 'public');

                $dimensions = null;
                if (str_starts_with($mime, 'image/')) {
                    $imageInfo = @getimagesize($file->getRealPath());
                    if ($imageInfo) {
                        $dimensions = "{$imageInfo[0]}x{$imageInfo[1]}";
                    }
                }

                $media = Media::create([
                    'name' => pathinfo($originalName, PATHINFO_FILENAME),
                    'file_name' => $originalName,
                    'file_path' => $path,
                    'mime_type' => $mime,
                    'file_size' => $size,
                    'dimensions' => $dimensions,
                    'uploader_id' => Auth::id(),
                ]);

                $uploaded[] = $media;
            }

            ActivityLog::log('Upload Media', 'Mengunggah '.count($uploaded).' file ke media library');
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'files' => $uploaded]);
        }

        return redirect()->route('admin.media.index')->with('success', 'File berhasil diunggah.');
    }

    public function destroy(Media $media): RedirectResponse
    {
        if (Storage::disk('public')->exists($media->file_path)) {
            Storage::disk('public')->delete($media->file_path);
        }

        $media->delete();
        ActivityLog::log('Hapus Media', "Menghapus media '{$media->name}'");

        return redirect()->route('admin.media.index')->with('success', 'Media berhasil dihapus.');
    }
}
