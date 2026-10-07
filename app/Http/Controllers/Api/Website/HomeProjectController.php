<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\models\website\HomeProjectMeta;
use App\models\website\HomeProjectSpec;
use Illuminate\Http\Request;

class HomeProjectController extends Controller
{
    public function createOrUpdate(Request $request)
    {
        $meta = HomeProjectMeta::first() ?: new HomeProjectMeta();
        $meta->eyebrow = $request->eyebrow ?? '';
        $meta->title = $request->title ?? '';
        $meta->paragraph_1 = $request->paragraph_1 ?? '';
        $meta->paragraph_2 = $request->paragraph_2 ?? '';
        $meta->image = $request->image ?? '';
        $meta->image_alt = $request->image_alt ?? '';
        $meta->caption = $request->caption ?? '';
        $meta->save();

        HomeProjectSpec::truncate();
        foreach ($request->items ?? [] as $i => $item) {
            HomeProjectSpec::create([
                'label' => $item['label'] ?? '',
                'value' => $item['value'] ?? '',
                'sort' => $item['sort'] ?? ($i + 1),
                'status' => $item['status'] ?? 1,
            ]);
        }

        return response()->json(['message' => 'success'], 200);
    }

    public function list()
    {
        return response()->json([
            'data' => HomeProjectSpec::orderBy('sort')->orderBy('id')->get(),
            'meta' => HomeProjectMeta::first(),
            'message' => 'success',
        ], 200);
    }
}
