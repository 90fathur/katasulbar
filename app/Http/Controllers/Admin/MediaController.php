<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Media;
use App\Services\ImageOptimizer;
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
            'files.*' => 'nullable|file|mimes:jpg,jpeg,png,webp,gif,mp4,webm,pdf|max:10240',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,webp,gif,mp4,webm,pdf|max:10240',
        ]);

        $uploaded = [];
        $filesToProcess = [];

        if ($request->hasFile('files')) {
            $filesToProcess = $request->file('files');
        } elseif ($request->hasFile('file')) {
            $filesToProcess = [$request->file('file')];
        }

        foreach ($filesToProcess as $file) {
            $originalName = $file->getClientOriginalName();
            $mime = $file->getMimeType();
            $path = $file->store('media', 'public');

            $fullPath = storage_path('app/public/'.$path);
            if (str_starts_with($mime, 'image/')) {
                ImageOptimizer::optimizeForWeb($fullPath);
            }

            $size = file_exists($fullPath) ? filesize($fullPath) : $file->getSize();

            $dimensions = null;
            if (str_starts_with($mime, 'image/')) {
                $imageInfo = @getimagesize($fullPath);
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

        if (count($uploaded) > 0) {
            ActivityLog::log('Upload Media', 'Mengunggah '.count($uploaded).' file ke media library');
        }

        if ($request->expectsJson() || $request->ajax()) {
            $firstUrl = ! empty($uploaded) ? asset('storage/'.$uploaded[0]->file_path) : null;

            return response()->json([
                'success' => true,
                'files' => $uploaded,
                'url' => $firstUrl,
            ]);
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
