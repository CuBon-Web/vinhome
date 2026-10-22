<?php

namespace App\Http\Controllers\Api\Website;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\models\website\Banner;

class BannerController extends Controller
{
    public function createOrUpdate(Request $request)
    {
    	if($request->data){
    		Banner::truncate();

	    	foreach ($request->data as $key => $value) {
                $type = isset($value['type']) && $value['type'] === 'youtube' ? 'youtube' : 'image';

	    		Banner::create([
                    'image' => $value['image'] ?? '',
                    'type' => $type,
                    'video_url' => $type === 'youtube' ? ($value['video_url'] ?? '') : null,
                    'status' => $value['status'],
                    'title' => $value['title'] ?? '',
                    'description' => $value['description'] ?? '',
                    'link' => $this->encodeHints($value['link'] ?? ''),
                ]);
	    	}
    	}
    	return response()->json([
            'messenge' => 'success'
        ],200);
    }

    private function encodeHints($value)
    {
        $items = $value;
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                $items = $decoded;
            } else {
                $plain = trim($value);
                $items = $plain === '' ? [] : [['text' => '', 'link' => $plain]];
            }
        }

        if (!is_array($items)) {
            return json_encode([], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        $clean = [];
        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }
            $text = trim((string) ($item['text'] ?? ''));
            $url = trim((string) ($item['link'] ?? ''));
            if ($text === '' && $url === '') {
                continue;
            }
            $clean[] = ['text' => $text, 'link' => $url];
        }

        return json_encode($clean, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    public function list()
    {
    	$data = Banner::get();
    	return response()->json([
            'messenge' => 'success',
            'data' => $data
        ],200);
    }
}
