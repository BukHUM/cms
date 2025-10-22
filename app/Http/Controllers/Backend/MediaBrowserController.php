<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MediaBrowserController extends Controller
{
    protected $allowedTypes = [
        'images' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'],
        'icons' => ['ico', 'png', 'svg'],
        'documents' => ['pdf', 'doc', 'docx', 'txt'],
        'videos' => ['mp4', 'avi', 'mov', 'wmv'],
        'audio' => ['mp3', 'wav', 'ogg']
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display media browser
     */
    public function index(Request $request)
    {
        $currentPath = $request->get('path', '');
        $search = $request->get('search', '');
        $type = $request->get('type', 'all');
        
        // Get files and folders
        $mediaData = $this->getMediaData($currentPath, $search, $type);
        
        // Check if it's an AJAX request or has ajax parameter
        if ($request->ajax() || $request->has('ajax')) {
            return response()->json([
                'success' => true,
                'data' => $mediaData
            ]);
        }
        
        // For direct access, return HTML view
        return view('backend.media-browser.index', compact('mediaData', 'currentPath', 'search', 'type'));
    }

    /**
     * Display Elfinder interface
     */
    public function elfinder(Request $request)
    {
        try {
            // Use Elfinder's built-in view
            return app(\Barryvdh\Elfinder\ElfinderController::class)->showIndex($request);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการโหลด Elfinder: ' . $e->getMessage());
        }
    }

    /**
     * Upload new file(s)
     */
    public function upload(Request $request)
    {
        $request->validate([
            'files' => 'required|array|max:10', // Max 10 files
            'files.*' => 'file|max:10240', // 10MB max per file
            'path' => 'nullable|string'
        ]);

        try {
            $files = $request->file('files');
            $path = $request->input('path', '');
            $folder = $this->getFolderFromPath($path);
            $uploadedFiles = [];
            
            foreach ($files as $file) {
                // Generate unique filename
                $filename = $this->generateUniqueFilename($file->getClientOriginalName(), $folder);
                
                // Store file
                $fullPath = $folder ? "{$folder}/{$filename}" : $filename;
                $storedPath = $file->storeAs('media' . ($folder ? "/{$folder}" : ''), $filename, 'media');
                
                $uploadedFiles[] = [
                    'name' => $filename,
                    'path' => $fullPath,
                    'url' => asset('media/' . $fullPath),
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType(),
                    'extension' => strtolower($file->getClientOriginalExtension())
                ];
            }
            
            return response()->json([
                'success' => true,
                'message' => 'อัปโหลดไฟล์เรียบร้อยแล้ว',
                'files' => $uploadedFiles
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการอัปโหลด: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create new folder
     */
    public function createFolder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-Z0-9_-]+$/',
            'path' => 'nullable|string'
        ]);

        try {
            $folderName = $request->input('name');
            $currentPath = $request->input('path', '');
            $folder = $this->getFolderFromPath($currentPath);
            
            $fullPath = $folder ? "{$folder}/{$folderName}" : $folderName;
            $storagePath = 'media' . ($folder ? "/{$folder}" : '') . "/{$folderName}";
            
            if (Storage::disk('media')->exists($storagePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'โฟลเดอร์นี้มีอยู่แล้ว'
                ], 400);
            }
            
            Storage::disk('media')->makeDirectory($storagePath);
            
            return response()->json([
                'success' => true,
                'message' => 'สร้างโฟลเดอร์เรียบร้อยแล้ว',
                'folder' => [
                    'name' => $folderName,
                    'path' => $fullPath
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการสร้างโฟลเดอร์: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete file or folder
     */
    public function delete(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
            'type' => 'required|in:file,folder'
        ]);

        try {
            $path = $request->input('path');
            $type = $request->input('type');
            $fullPath = 'media' . "/{$path}";
            
            if (!Storage::disk('media')->exists($fullPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'ไม่พบไฟล์หรือโฟลเดอร์'
                ], 404);
            }
            
            if ($type === 'folder') {
                Storage::disk('media')->deleteDirectory($fullPath);
            } else {
                Storage::disk('media')->delete($fullPath);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'ลบเรียบร้อยแล้ว'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการลบ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rename file or folder
     */
    public function rename(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
            'new_name' => 'required|string|max:255|regex:/^[a-zA-Z0-9._-]+$/',
            'type' => 'required|in:file,folder'
        ]);

        try {
            $path = $request->input('path');
            $newName = $request->input('new_name');
            $type = $request->input('type');
            
            $oldPath = 'media' . "/{$path}";
            $pathInfo = pathinfo($path);
            $newPath = 'media' . "/{$pathInfo['dirname']}/{$newName}";
            
            if ($type === 'file' && isset($pathInfo['extension'])) {
                $newPath .= '.' . $pathInfo['extension'];
            }
            
            if (Storage::disk('media')->exists($newPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'ชื่อนี้มีอยู่แล้ว'
                ], 400);
            }
            
            Storage::disk('media')->move($oldPath, $newPath);
            
            return response()->json([
                'success' => true,
                'message' => 'เปลี่ยนชื่อเรียบร้อยแล้ว',
                'new_path' => str_replace('media/', '', $newPath)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการเปลี่ยนชื่อ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get media data for current path
     */
    private function getMediaData($currentPath, $search = '', $type = 'all')
    {
        $folder = $this->getFolderFromPath($currentPath);
        $storagePath = $folder ? "/{$folder}" : '';
        
        $files = [];
        $folders = [];
        
        if (Storage::disk('media')->exists($storagePath) || $storagePath === '') {
            $items = Storage::disk('media')->files($storagePath);
            $directories = Storage::disk('media')->directories($storagePath);
            
            // Process files
            foreach ($items as $item) {
                $relativePath = $item;
                $pathInfo = pathinfo($relativePath);
                $extension = strtolower($pathInfo['extension'] ?? '');
                
                // Filter by type
                if ($type !== 'all') {
                    $typeExtensions = $this->allowedTypes[$type] ?? [];
                    if (!in_array($extension, $typeExtensions)) {
                        continue;
                    }
                }
                
                // Filter by search
                if ($search && !Str::contains(strtolower($pathInfo['filename']), strtolower($search))) {
                    continue;
                }
                
                $files[] = [
                    'name' => $pathInfo['basename'],
                    'path' => $relativePath,
                    'url' => asset('media/' . $relativePath),
                    'size' => Storage::disk('media')->size($item),
                    'modified' => Storage::disk('media')->lastModified($item),
                    'extension' => $extension,
                    'type' => $this->getFileType($extension)
                ];
            }
            
            // Process folders
            foreach ($directories as $directory) {
                $relativePath = $directory;
                $folderName = basename($directory);
                
                // Filter by search
                if ($search && !Str::contains(strtolower($folderName), strtolower($search))) {
                    continue;
                }
                
                $folders[] = [
                    'name' => $folderName,
                    'path' => $relativePath,
                    'modified' => time()
                ];
            }
        }
        
        // Sort files and folders
        usort($files, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });
        
        usort($folders, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });
        
        return [
            'files' => $files,
            'folders' => $folders,
            'current_path' => $currentPath,
            'breadcrumbs' => $this->getBreadcrumbs($currentPath)
        ];
    }

    /**
     * Get folder from path
     */
    private function getFolderFromPath($path)
    {
        return $path ? str_replace('/', DIRECTORY_SEPARATOR, $path) : '';
    }

    /**
     * Generate unique filename
     */
    private function generateUniqueFilename($originalName, $folder)
    {
        $pathInfo = pathinfo($originalName);
        $filename = $pathInfo['filename'];
        $extension = $pathInfo['extension'] ?? '';
        
        $counter = 1;
        $newName = $originalName;
        
        $storagePath = 'media' . ($folder ? "/{$folder}" : '');
        
        while (Storage::disk('media')->exists($storagePath . "/{$newName}")) {
            $newName = "{$filename}_{$counter}.{$extension}";
            $counter++;
        }
        
        return $newName;
    }

    /**
     * Get file type from extension
     */
    private function getFileType($extension)
    {
        foreach ($this->allowedTypes as $type => $extensions) {
            if (in_array($extension, $extensions)) {
                return $type;
            }
        }
        return 'other';
    }

    /**
     * Get breadcrumbs for current path
     */
    private function getBreadcrumbs($currentPath)
    {
        $breadcrumbs = [
            ['name' => 'Media', 'path' => '']
        ];
        
        if ($currentPath) {
            $parts = explode('/', $currentPath);
            $path = '';
            
            foreach ($parts as $part) {
                $path .= ($path ? '/' : '') . $part;
                $breadcrumbs[] = [
                    'name' => $part,
                    'path' => $path
                ];
            }
        }
        
        return $breadcrumbs;
    }
}
