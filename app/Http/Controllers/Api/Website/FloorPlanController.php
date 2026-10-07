<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\models\website\FloorPlan;
use Illuminate\Http\Request;

class FloorPlanController extends Controller
{
    public function list()
    {
        $data = FloorPlan::with('category:id,name')
            ->orderBy('sort')
            ->orderBy('id')
            ->get(['id', 'category_id', 'name', 'slug', 'building_name', 'sort', 'status']);

        return response()->json(['data' => $data, 'message' => 'success'], 200);
    }

    public function edit($id)
    {
        $data = FloorPlan::find($id);
        if (!$data) {
            return response()->json(['message' => 'Không tìm thấy bài mặt bằng'], 404);
        }

        return response()->json(['data' => $data, 'message' => 'success'], 200);
    }

    public function createOrUpdate(Request $request)
    {
        $name = trim($request->input('name', ''));
        if ($name === '') {
            return response()->json(['message' => 'Tên trên menu không được để trống'], 422);
        }
        if (!$request->input('category_id')) {
            return response()->json(['message' => 'Hãy chọn danh mục'], 422);
        }

        $id = $request->input('id');
        $model = $id ? FloorPlan::find($id) : new FloorPlan();
        if ($id && !$model) {
            return response()->json(['message' => 'Không tìm thấy bài mặt bằng'], 404);
        }

        $slug = to_slug($request->input('slug') ?: $name);
        if ($slug === '') {
            $slug = 'mat-bang';
        }
        $base = $slug;
        $n = 2;
        while (FloorPlan::where('slug', $slug)->when($model->id, function ($query) use ($model) {
            $query->where('id', '!=', $model->id);
        })->exists()) {
            $slug = $base.'-'.$n;
            $n++;
        }

        $model->category_id = $request->input('category_id');
        $model->name = $name;
        $model->slug = $slug;
        $model->eyebrow = trim($request->input('eyebrow', ''));
        $model->title = trim($request->input('title', ''));
        $model->intro = trim($request->input('intro', ''));
        $model->building_name = trim($request->input('building_name', ''));
        $model->footnote_title = trim($request->input('footnote_title', ''));
        $model->footnote = $request->input('footnote', '');
        $model->content = $request->input('content', '');
        $model->journal = $this->cleanJournal($request->input('journal', []));
        $model->sort = $request->input('sort', 0);
        $model->status = $request->input('status', 1);
        $model->save();

        return response()->json(['data' => $model, 'message' => 'success'], 200);
    }

    public function delete($id)
    {
        FloorPlan::where('id', $id)->delete();

        return response()->json(['message' => 'success'], 200);
    }

    private function cleanJournal($journal)
    {
        if (!is_array($journal)) {
            return [];
        }

        $days = [];
        foreach ($journal as $day) {
            if (!is_array($day)) {
                continue;
            }
            $sale = $this->journalLines($day['sale'] ?? []);
            $rent = $this->journalLines($day['rent'] ?? []);
            $date = trim($day['date'] ?? '');
            $dow = trim($day['dow'] ?? '');
            $time = trim($day['time'] ?? '');
            if ($date === '' && $dow === '' && !count($sale) && !count($rent)) {
                continue;
            }
            $days[] = [
                'dow' => $dow,
                'date' => $date,
                'time' => $time,
                'sale' => $sale,
                'rent' => $rent,
            ];
        }

        return $days;
    }

    private function journalLines($value)
    {
        if (is_string($value)) {
            $value = preg_split('/\r\n|\r|\n/', $value);
        }
        if (!is_array($value)) {
            return [];
        }

        $lines = [];
        foreach ($value as $line) {
            $line = trim((string) $line);
            if ($line !== '') {
                $lines[] = $line;
            }
        }

        return $lines;
    }
}
