<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SeedMuaThueProductMenu extends Migration
{
    public function up()
    {
        $now = now();
        $menus = [
            [
                'name' => 'Mua',
                'slug' => 'mua',
                'sort' => 7,
                'types' => [
                    [
                        'name' => 'Vinhomes Central Park',
                        'slug' => 'vinhomes-central-park',
                        'children' => [
                            'Tất cả phòng ngủ',
                            '1 phòng ngủ',
                            '2 phòng ngủ',
                            '3 phòng ngủ',
                            '4 phòng ngủ',
                            'Tin rao bán',
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Thuê',
                'slug' => 'thue',
                'sort' => 8,
                'types' => [
                    [
                        'name' => 'Vinhomes Central Park',
                        'slug' => 'vinhomes-central-park',
                        'children' => [
                            'Tất cả phòng ngủ',
                            '1 phòng ngủ',
                            '2 phòng ngủ',
                            '3 phòng ngủ',
                            '4 phòng ngủ',
                        ],
                    ],
                ],
            ],
        ];

        foreach ($menus as $menu) {
            $cate = DB::table('product_category')->where('slug', $menu['slug'])->first();
            if (!$cate) {
                $cateId = DB::table('product_category')->insertGetId([
                    'quiz_id' => 0,
                    'name' => $this->langName($menu['name']),
                    'slug' => $menu['slug'],
                    'language' => '0',
                    'imagehome' => '[]',
                    'status' => 1,
                    'sort' => $menu['sort'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            } else {
                $cateId = $cate->id;
            }

            foreach ($menu['types'] as $type) {
                $typeRow = DB::table('product_type')
                    ->where('cate_id', $cateId)
                    ->where('slug', $type['slug'])
                    ->first();
                if (!$typeRow) {
                    $typeId = DB::table('product_type')->insertGetId([
                        'quiz_id' => 0,
                        'language' => '0',
                        'name' => $this->langName($type['name']),
                        'slug' => $type['slug'],
                        'cate_id' => $cateId,
                        'cate_slug' => $menu['slug'],
                        'status' => 1,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                } else {
                    $typeId = $typeRow->id;
                }

                foreach ($type['children'] as $childName) {
                    $childSlug = to_slug($childName);
                    $exists = DB::table('product_type_two')
                        ->where('type_id', $typeId)
                        ->where('slug', $childSlug)
                        ->exists();
                    if ($exists) {
                        continue;
                    }
                    DB::table('product_type_two')->insert([
                        'name' => $this->langName($childName),
                        'slug' => $childSlug,
                        'cate_id' => $cateId,
                        'cate_slug' => $menu['slug'],
                        'type_id' => $typeId,
                        'type_slug' => $type['slug'],
                        'status' => 1,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }
    }

    public function down()
    {
        $ids = DB::table('product_category')->whereIn('slug', ['mua', 'thue'])->pluck('id');
        if ($ids->isEmpty()) {
            return;
        }
        DB::table('product_type_two')->whereIn('cate_id', $ids)->delete();
        DB::table('product_type')->whereIn('cate_id', $ids)->delete();
        DB::table('product_category')->whereIn('id', $ids)->delete();
    }

    private function langName($name)
    {
        return json_encode([
            ['lang_code' => 'vi', 'content' => $name],
        ], JSON_UNESCAPED_UNICODE);
    }
}
