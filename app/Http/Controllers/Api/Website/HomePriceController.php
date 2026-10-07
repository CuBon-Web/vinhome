<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\models\website\HomePriceItem;
use App\models\website\HomePriceMeta;
use Illuminate\Http\Request;

class HomePriceController extends Controller
{
    public function createOrUpdate(Request $request)
    {
        $meta = HomePriceMeta::first() ?: new HomePriceMeta();
        $meta->eyebrow = $request->eyebrow ?? '';
        $meta->title = $request->title ?? '';
        $meta->note = $request->note ?? '';
        $meta->save();

        HomePriceItem::truncate();
        foreach ($request->items ?? [] as $i => $item) {
            HomePriceItem::create([
                'type' => $item['type'] ?? '',
                'area' => $item['area'] ?? '',
                'price' => $item['price'] ?? '',
                'sort' => $item['sort'] ?? ($i + 1),
                'status' => $item['status'] ?? 1,
            ]);
        }

        return response()->json(['message' => 'success'], 200);
    }

    public function list()
    {
        return response()->json([
            'data' => HomePriceItem::orderBy('sort')->orderBy('id')->get(),
            'meta' => HomePriceMeta::first(),
            'message' => 'success',
        ], 200);
    }
}
