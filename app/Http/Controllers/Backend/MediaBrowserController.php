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
            // Get query parameters for filtering
            $search = $request->get('search');
            $type = $request->get('type');
            $dateFrom = $request->get('date_from');
            $dateTo = $request->get('date_to');
            
            // Build query
            $query = Media::query();
            
            // Search by name
            if ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            }
            
            // Filter by file type
            if ($type) {
                switch ($type) {
                    case 'images':
                        $query->where('mime_type', 'like', 'image/%');
                        break;
                    case 'documents':
                        $query->whereIn('mime_type', [
                            'application/pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'text/plain',
                            'text/csv',
                            'text/html',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        ]);
                        break;
                    case 'pdf':
                        $query->where('mime_type', 'application/pdf');
                        break;
                    case 'word':
                        $query->whereIn('mime_type', [
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                        ]);
                        break;
                    case 'excel':
                        $query->whereIn('mime_type', [
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        ]);
                        break;
                    default:
                        if (strpos($type, '/') !== false) {
                            $query->where('mime_type', $type);
                        }
                        break;
                }
            }
            
            // Filter by date
            if ($dateFrom) {
                $query->whereDate('created_at', '>=', $dateFrom);
            }
            if ($dateTo) {
                $query->whereDate('created_at', '<=', $dateTo);
            }
            
            // Get filtered media files
            $mediaFiles = $query->orderBy('created_at', 'desc')->get();
            
            // Get file type statistics
            $fileTypes = [
                'images' => Media::where('mime_type', 'like', 'image/%')->count(),
                'documents' => Media::whereIn('mime_type', [
                    'application/pdf',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'text/plain',
                    'text/csv',
                    'text/html',
                    'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                ])->count(),
                'pdf' => Media::where('mime_type', 'application/pdf')->count(),
                'word' => Media::whereIn('mime_type', [
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                ])->count(),
                'excel' => Media::whereIn('mime_type', [
                    'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                ])->count(),
            ];
            
            return view('backend.media-browser.index', compact(
                'mediaFiles', 
                'fileTypes',
                'search',
                'type',
                'dateFrom',
                'dateTo'
            ));
            
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Media Browser Error: ' . $e->getMessage());
            
            // Return empty data
            $mediaFiles = collect([]);
            $fileTypes = [];
            
            return view('backend.media-browser.index', compact('mediaFiles', 'fileTypes'))
                ->with('error', 'เกิดข้อผิดพลาดในการโหลด Media Browser: ' . $e->getMessage());
        }
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
                    
                    // Get the actual stored file path
                    $storedFilePath = storage_path('app/public/' . $filePath);
                    
                    // Auto-resize images if they're too large
                    if (str_starts_with($file->getMimeType(), 'image/')) {
                        $this->processImageOptimization($storedFilePath, $file->getMimeType());
                    }
                    
                    // Get final file size after processing
                    $finalFileSize = filesize($storedFilePath);
                    
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
                    $media->size = $finalFileSize;
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
            $customProperties = $media->custom_properties ?? [];
            
            return response()->json([
                'id' => $media->id,
                'name' => $media->name,
                'url' => $media->getUrl(),
                'size' => $media->size,
                'mime_type' => $media->mime_type,
                'collection_name' => $media->collection_name,
                'alt_text' => $customProperties['alt_text'] ?? '',
                'description' => $customProperties['description'] ?? '',
                'tags' => $customProperties['tags'] ?? '',
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
     * Update media file information
     */
    public function update(Request $request, $mediaId)
    {
        try {
            $media = Media::findOrFail($mediaId);
            
            $request->validate([
                'name' => 'required|string|max:255',
                'alt_text' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:1000',
                'tags' => 'nullable|string|max:500',
                'collection_name' => 'nullable|string|max:100'
            ]);
            
            // Update media information
            $media->name = $request->input('name');
            $media->collection_name = $request->input('collection_name', $media->collection_name);
            
            // Store additional information in custom_properties
            $customProperties = $media->custom_properties ?? [];
            $customProperties['alt_text'] = $request->input('alt_text');
            $customProperties['description'] = $request->input('description');
            $customProperties['tags'] = $request->input('tags');
            $media->custom_properties = $customProperties;
            
            $media->save();
            
            return response()->json([
                'success' => true,
                'message' => 'อัปเดตข้อมูลไฟล์สำเร็จ',
                'media' => [
                    'id' => $media->id,
                    'name' => $media->name,
                    'alt_text' => $customProperties['alt_text'] ?? '',
                    'description' => $customProperties['description'] ?? '',
                    'tags' => $customProperties['tags'] ?? '',
                    'collection_name' => $media->collection_name
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการอัปเดตไฟล์: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk operations for multiple media files
     */
    public function bulkOperation(Request $request)
    {
        try {
            $request->validate([
                'action' => 'required|in:delete,download,change_collection,move',
                'media_ids' => 'required|array|min:1',
                'media_ids.*' => 'integer|exists:core_media,id',
                'collection_name' => 'nullable|string|max:100',
                'folder_path' => 'nullable|string|max:500'
            ]);
            
            $action = $request->input('action');
            $mediaIds = $request->input('media_ids');
            $mediaFiles = Media::whereIn('id', $mediaIds)->get();
            
            switch ($action) {
                case 'delete':
                    return $this->bulkDelete($mediaFiles);
                case 'download':
                    return $this->bulkDownload($mediaFiles);
                case 'change_collection':
                    return $this->bulkChangeCollection($mediaFiles, $request->input('collection_name'));
                case 'move':
                    return $this->bulkMove($mediaFiles, $request->input('folder_path'));
                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'การดำเนินการไม่ถูกต้อง'
                    ], 400);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการดำเนินการ: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Bulk delete media files
     */
    private function bulkDelete($mediaFiles)
    {
        $deletedCount = 0;
        
        foreach ($mediaFiles as $media) {
            try {
                $filePath = $media->getPath();
                $media->delete();
                
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                $deletedCount++;
            } catch (\Exception $e) {
                \Log::error('Bulk delete error for media ' . $media->id . ': ' . $e->getMessage());
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "ลบไฟล์สำเร็จ {$deletedCount} ไฟล์"
        ]);
    }
    
    /**
     * Bulk download media files as ZIP
     */
    private function bulkDownload($mediaFiles)
    {
        try {
            $zip = new \ZipArchive();
            $zipFileName = 'media_files_' . now()->format('Y-m-d_H-i-s') . '.zip';
            $zipPath = storage_path('app/temp/' . $zipFileName);
            
            // Create temp directory if not exists
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }
            
            if ($zip->open($zipPath, \ZipArchive::CREATE) !== TRUE) {
                throw new \Exception('ไม่สามารถสร้างไฟล์ ZIP ได้');
            }
            
            foreach ($mediaFiles as $media) {
                $filePath = $media->getPath();
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $media->name);
                }
            }
            
            $zip->close();
            
            return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการสร้างไฟล์ ZIP: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Bulk change collection
     */
    private function bulkChangeCollection($mediaFiles, $collectionName)
    {
        $updatedCount = 0;
        
        foreach ($mediaFiles as $media) {
            try {
                $media->collection_name = $collectionName;
                $media->save();
                $updatedCount++;
            } catch (\Exception $e) {
                \Log::error('Bulk change collection error for media ' . $media->id . ': ' . $e->getMessage());
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "เปลี่ยนคอลเลกชันสำเร็จ {$updatedCount} ไฟล์"
        ]);
    }
    
    /**
     * Bulk move files (placeholder - would need file system operations)
     */
    private function bulkMove($mediaFiles, $folderPath)
    {
        // This would require complex file system operations
        // For now, return a message that this feature is not implemented
        return response()->json([
            'success' => false,
            'message' => 'ฟีเจอร์ย้ายไฟล์จะเพิ่มในอนาคต'
        ]);
    }
    
    /**
     * Process image optimization
     */
    private function processImageOptimization($tempPath, $mimeType)
    {
        try {
            // Get image dimensions
            $imageInfo = getimagesize($tempPath);
            
            if (!$imageInfo) {
                return;
            }
            
            $width = $imageInfo[0];
            $height = $imageInfo[1];
            
            // If image is larger than 1200px, resize it
            if ($width > 1200 || $height > 1200) {
                $this->resizeImage($tempPath, $tempPath, 1200, 1200, $mimeType);
            }
            
        } catch (\Exception $e) {
            \Log::error('Image optimization error: ' . $e->getMessage());
        }
    }
    
    /**
     * Resize image maintaining aspect ratio
     */
    private function resizeImage($sourcePath, $destinationPath, $maxWidth, $maxHeight, $mimeType = null)
    {
        $imageInfo = getimagesize($sourcePath);
        $width = $imageInfo[0];
        $height = $imageInfo[1];
        
        // Use provided mimeType or get from image info
        if (!$mimeType) {
            $mimeType = $imageInfo['mime'];
        }
        
        // Calculate new dimensions maintaining aspect ratio
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = intval($width * $ratio);
        $newHeight = intval($height * $ratio);
        
        // Create source image resource
        switch ($mimeType) {
            case 'image/jpeg':
                $sourceImage = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $sourceImage = imagecreatefrompng($sourcePath);
                break;
            case 'image/gif':
                $sourceImage = imagecreatefromgif($sourcePath);
                break;
            case 'image/webp':
                $sourceImage = imagecreatefromwebp($sourcePath);
                break;
            default:
                return false;
        }
        
        if (!$sourceImage) {
            return false;
        }
        
        // Create destination image
        $destinationImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preserve transparency for PNG and GIF
        if ($mimeType == 'image/png' || $mimeType == 'image/gif') {
            imagealphablending($destinationImage, false);
            imagesavealpha($destinationImage, true);
            $transparent = imagecolorallocatealpha($destinationImage, 255, 255, 255, 127);
            imagefilledrectangle($destinationImage, 0, 0, $newWidth, $newHeight, $transparent);
        }
        
        // Resize image
        imagecopyresampled($destinationImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        
        // Save resized image
        $result = false;
        switch ($mimeType) {
            case 'image/jpeg':
                $result = imagejpeg($destinationImage, $destinationPath, 85); // 85% quality
                break;
            case 'image/png':
                $result = imagepng($destinationImage, $destinationPath, 8); // Compression level 8
                break;
            case 'image/gif':
                $result = imagegif($destinationImage, $destinationPath);
                break;
            case 'image/webp':
                $result = imagewebp($destinationImage, $destinationPath, 85); // 85% quality
                break;
        }
        
        // Clean up memory
        imagedestroy($sourceImage);
        imagedestroy($destinationImage);
        
        return $result;
    }

    /**
     * Delete media file
     */
    public function delete($mediaId)
    {
        try {
            $media = Media::findOrFail($mediaId);
            
            // Get file path before deleting from database
            $filePath = $media->getPath();
            
            // Delete from database first
            $media->delete();
            
            // Delete actual file from storage
            if (file_exists($filePath)) {
                unlink($filePath);
                
                // Check if folder is empty and delete it
                $folderPath = dirname($filePath);
                if (is_dir($folderPath) && count(scandir($folderPath)) == 2) { // Only . and .. entries
                    rmdir($folderPath);
                }
            }
            
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