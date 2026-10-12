<?php

namespace App\models\website;

use Illuminate\Database\Eloquent\Model;

class FloorPlan extends Model
{
    protected $table = 'floor_plans';

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'eyebrow',
        'title',
        'intro',
        'building_name',
        'footnote_title',
        'footnote',
        'content',
        'journal',
        'sort',
        'status',
        'featured',
    ];

    protected $casts = [
        'journal' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(FloorPlanCategory::class, 'category_id');
    }
}
