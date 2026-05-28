<?php

namespace App\Services;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class DateBasedPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        // Use date-based folder structure (YYYY-MM-DD)
        $dateFolder = $media->created_at->format('Y-m-d');
        
        return $dateFolder . '/';
    }

    public function getPathForConversions(Media $media): string
    {
        // Use same date-based folder for conversions
        $dateFolder = $media->created_at->format('Y-m-d');
        
        return $dateFolder . '/conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        // Use same date-based folder for responsive images
        $dateFolder = $media->created_at->format('Y-m-d');
        
        return $dateFolder . '/responsive-images/';
    }
}
