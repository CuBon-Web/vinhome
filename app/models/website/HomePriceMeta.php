<?php

namespace App\models\website;

use Illuminate\Database\Eloquent\Model;

class HomePriceMeta extends Model
{
    protected $table = 'home_price_meta';
    protected $fillable = ['eyebrow', 'title', 'note'];
}
