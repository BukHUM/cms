<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;

class MediaModel extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'core_media_models';
    
    protected $fillable = [
        'name',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('default')
            ->useDisk('public')
            ->acceptsMimeTypes([
                'image/jpeg', 'image/png', 'image/gif', 'image/webp',
                'text/plain', 'text/csv', 'text/html',
                'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/zip', 'application/x-rar-compressed'
            ]);
            
        $this->addMediaCollection('images')
            ->useDisk('public')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
            
        $this->addMediaCollection('documents')
            ->useDisk('public')
            ->acceptsMimeTypes([
                'application/pdf', 
                'application/msword', 
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'text/plain', 'text/csv', 'text/html',
                'application/vnd.ms-excel', 
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ]);
    }

    public function registerMediaConversions(SpatieMedia $media = null): void
    {
        // No conversions needed - we handle resizing manually during upload
    }

    /**
     * Override the path generator to use date-based folders
     */
    public function getPathGeneratorClass(): string
    {
        return \App\Services\DateBasedPathGenerator::class;
    }

    /**
     * Override the media path to use date-based folders
     */
    public function getMediaPath(string $collectionName = 'default'): string
    {
        $dateFolder = now()->format('Y-m-d');
        return $dateFolder . '/';
    }
}
