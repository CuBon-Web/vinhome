<?php

namespace App\models\website;

use Illuminate\Database\Eloquent\Model;

class HomeProjectMeta extends Model
{
    protected $table = 'home_project_meta';
    protected $fillable = ['eyebrow', 'title', 'paragraph_1', 'paragraph_2', 'image', 'image_alt', 'caption'];
}
