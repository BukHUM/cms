<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Media;
use App\Models\MediaModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class MediaBrowserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display Spatie Media Library interface
     */
    public function index(Request $request)
    {
        try {
            // Get all media files directly - same as SimpleMediaController
            $mediaFiles = Media::orderBy('created_at', 'desc')->get();
            
            // Get storage directories - same as SimpleMediaController
            $storagePath = storage_path('app/public');
            $directories = $this->getDirectories($storagePath);
            
            return view('backend.media-browser.index', compact('mediaFiles', 'directories'));
            
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Media Browser Error: ' . $e->getMessage());
            
            // Return empty data
            $mediaFiles = collect([]);
            $directories = [];
            
            return view('backend.media-browser.index', compact('mediaFiles', 'directories'))
                ->with('error', 'เกิดข้อผิดพลาดในการโหลด Media Browser: ' . $e->getMessage());
        }
    }

    /**
     * Get directories from storage - organized by date
     */
    private function getDirectories($path)
    {
        $directories = [];
        
        if (is_dir($path)) {
            $items = scandir($path);
            foreach ($items as $item) {
                if ($item != '.' && $item != '..' && is_dir($path . '/' . $item)) {
                    // Only show date-based folders (YYYY-MM-DD format)
                    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $item)) {
                        $directories[] = [
                            'name' => $item,
                            'path' => $path . '/' . $item,
                            'size' => $this->getDirectorySize($path . '/' . $item),
                            'date' => $item
                        ];
                    }
                }
            }
        }
        
        // Sort by date (newest first)
        usort($directories, function($a, $b) {
            return strcmp($b['date'], $a['date']);
        });
        
        return $directories;
    }
    
    /**
     * Get directory size - same as SimpleMediaController
     */
    private function getDirectorySize($path)
    {
        $size = 0;
        if (is_dir($path)) {
            $files = scandir($path);
            foreach ($files as $file) {
                if ($file != '.' && $file != '..') {
                    $filePath = $path . '/' . $file;
                    if (is_file($filePath)) {
                        $size += filesize($filePath);
                    }
                }
            }
        }
        return $size;
    }

    /**
     * Upload media files
     */
    public function upload(Request $request)
    {
        try {
            $request->validate([
                'media.*' => 'required|file|max:10240', // 10MB max
                'collection' => 'string|nullable'
            ]);

            $collection = $request->input('collection', 'default');
            $uploadedFiles = [];

            if ($request->hasFile('media')) {
                // Create a MediaModel instance for uploading
                $mediaModel = new MediaModel();
                $mediaModel->name = 'Media Upload';
                $mediaModel->save();

                foreach ($request->file('media') as $file) {
                    // Create date-based folder structure
                    $dateFolder = now()->format('Y-m-d');
                    $storagePath = storage_path('app/public/' . $dateFolder);
                    
                    // Create folder if it doesn't exist
                    if (!File::exists($storagePath)) {
                        File::makeDirectory($storagePath, 0755, true);
                    }

                    // Move file to date-based folder manually
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $dateFolder . '/' . $fileName;
                    
                    // Store file in date-based folder
                    $file->storeAs($dateFolder, $fileName, 'public');
                    
                    // Create media record manually
                    $media = new Media();
                    $media->model_type = get_class($mediaModel);
                    $media->model_id = $mediaModel->id;
                    $media->uuid = \Str::uuid();
                    $media->collection_name = $collection;
                    $media->name = $file->getClientOriginalName();
                    $media->file_name = $fileName;
                    $media->mime_type = $file->getMimeType();
                    $media->disk = 'public';
                    $media->conversions_disk = 'public';
                    $media->size = $file->getSize();
                    $media->manipulations = [];
                    $media->custom_properties = [];
                    $media->generated_conversions = [];
                    $media->responsive_images = [];
                    $media->save();
                    
                    $uploadedFiles[] = [
                        'id' => $media->id,
                        'name' => $media->name,
                        'url' => $media->getUrl(),
                        'size' => $media->size,
                        'mime_type' => $media->mime_type
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'อัปโหลดไฟล์สำเร็จ',
                'files' => $uploadedFiles
            ]);

        } catch (\Exception $e) {
            \Log::error('Media Upload Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการอัปโหลด: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show media file details
     */
    public function show($mediaId)
    {
        try {
            $media = Media::findOrFail($mediaId);
            
            return response()->json([
                'id' => $media->id,
                'name' => $media->name,
                'url' => $media->getUrl(),
                'size' => $media->size,
                'mime_type' => $media->mime_type,
                'created_at' => $media->created_at
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการโหลดไฟล์'
            ], 500);
        }
    }

    /**
     * Download media file
     */
    public function download($mediaId)
    {
        try {
            $media = Media::findOrFail($mediaId);
            return response()->download($media->getPath(), $media->name);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการดาวน์โหลดไฟล์');
        }
    }

    /**
     * Delete media file
     */
    public function delete($mediaId)
    {
        try {
            $media = Media::findOrFail($mediaId);
            $media->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'ลบไฟล์สำเร็จ'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการลบไฟล์: ' . $e->getMessage()
            ], 500);
        }
    }
}