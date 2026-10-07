<?php

namespace App\models\website;

use Illuminate\Database\Eloquent\Model;

class FloorPlanCategory extends Model
{
    protected $table = 'floor_plan_categories';

    protected $fillable = ['name', 'slug', 'sort', 'status'];

    public function posts()
    {
        return $this->hasMany(FloorPlan::class, 'category_id');
    }
}
