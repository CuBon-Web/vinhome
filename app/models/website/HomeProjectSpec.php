<?php

namespace App\models\website;

use Illuminate\Database\Eloquent\Model;

class HomeProjectSpec extends Model
{
    protected $table = 'home_project_specs';
    protected $fillable = ['label', 'value', 'sort', 'status'];
}
