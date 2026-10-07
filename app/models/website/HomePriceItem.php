<?php

namespace App\models\website;

use Illuminate\Database\Eloquent\Model;

class HomePriceItem extends Model
{
    protected $table = 'home_price_items';
    protected $fillable = ['type', 'area', 'price', 'sort', 'status'];
}
