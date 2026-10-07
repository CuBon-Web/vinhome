<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\models\website\FloorPlan;
use App\models\website\FloorPlanCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class FloorPlanCategoryController extends Controller
{
    public function createOrUpdate(Request $request)
    {
        $items = $request->items ?? [];
        $kept = [];
        $usedSlugs = FloorPlanCategory::pluck('slug')->all();

        foreach ($items as $i => $item) {
            $name = trim($item['name'] ?? '');
            if ($name === '') {
                continue;
            }

            $model = null;
            if (!empty($item['id'])) {
                $model = FloorPlanCategory::find($item['id']);
            }
            if (!$model) {
                $model = new FloorPlanCategory();
            }

            if (!$model->exists || $model->slug === '' || $model->slug === null) {
                $slug = to_slug($name);
                if ($slug === '') {
                    $slug = 'danh-muc-'.($i + 1);
                }
                $base = $slug;
                $n = 2;
                while (in_array($slug, $usedSlugs, true)) {
                    $slug = $base.'-'.$n;
                    $n++;
                }
                $model->slug = $slug;
                $usedSlugs[] = $slug;
            }

            $model->name = $name;
            $model->sort = $item['sort'] ?? ($i + 1);
            $model->status = $item['status'] ?? 1;
            $model->save();
            $kept[] = $model->id;
        }

        $removed = FloorPlanCategory::whereNotIn('id', $kept ?: [0])->pluck('id');
        if ($removed->count() && Schema::hasTable('floor_plans')) {
            FloorPlan::whereIn('category_id', $removed)->update(['category_id' => null]);
        }
        FloorPlanCategory::whereIn('id', $removed)->delete();

        return response()->json(['message' => 'success'], 200);
    }

    public function list()
    {
        $data = FloorPlanCategory::orderBy('sort')->orderBy('id')->get();

        return response()->json(['data' => $data, 'message' => 'success'], 200);
    }
}
