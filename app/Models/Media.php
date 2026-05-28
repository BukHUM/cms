<?php

namespace App\Models;

use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;

class Media extends SpatieMedia
{
    protected $table = 'core_media';
    
    /**
     * Override getUrl to use date-based path
     */
    public function getUrl(string $conversionName = ''): string
    {
        $dateFolder = $this->created_at->format('Y-m-d');
        $filePath = $dateFolder . '/' . $this->file_name;
        
        return asset('storage/' . $filePath);
    }
    
    /**
     * Override getPath to use date-based path
     */
    public function getPath(string $conversionName = ''): string
    {
        $dateFolder = $this->created_at->format('Y-m-d');
        $filePath = $dateFolder . '/' . $this->file_name;
        
        return storage_path('app/public/' . $filePath);
    }
}