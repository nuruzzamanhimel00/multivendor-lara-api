<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Enums\BorderType;
use Spatie\Image\Enums\CropPosition;

class TestModel extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = [];

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
        $this
            ->addMediaConversion('preview_again')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }

    // public function registerMediaConversions(?Media $media = null): void
    // {
    //     $this->addMediaConversion('thumb')
    //           ->width(368)
    //           ->height(232)
    //           ->sharpen(10);

    //     $this->addMediaConversion('old-picture')
    //           ->sepia()
    //           ->border(10, BorderType::Overlay, 'black');

    //     $this->addMediaConversion('thumb-cropped')
    //         ->crop(400, 400, CropPosition::Center); // Trim or crop the image to the center for specified width and height.
    // }

}
