<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['image_path', 'caption', 'instagram_url', 'sort'])]
class GalleryItem extends Model
{
    public function url(): string
    {
        return asset('storage/'.$this->image_path);
    }
}
