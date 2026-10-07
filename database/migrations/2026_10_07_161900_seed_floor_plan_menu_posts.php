<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SeedFloorPlanMenuPosts extends Migration
{
    public function up()
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('floor_plans')) {
            return;
        }

        $groups = [
            'Vinhomes Central Park' => [
                'Mặt bằng tổng quan',
            ],
            'Khu Central' => [
                'Layout Central 1',
                'Layout Central 2',
                'Layout Central 3',
            ],
            'Khu Landmark' => [
                'Layout Landmark 1',
                'Layout Landmark 2',
                'Layout Landmark 3',
                'Layout Landmark 4',
                'Layout Landmark 5',
                'Layout Landmark 6',
                'Layout Landmark Plus',
                'Layout Landmark 81',
            ],
            'Khu Park' => [
                'Layout Park 1',
                'Layout Park 2',
                'Layout Park 3',
                'Layout Park 4',
                'Layout Park 5',
                'Layout Park 6',
                'Layout Park 7',
            ],
            'Vinhomes Golden River' => [
                'Mặt bằng tổng quan',
                'Layout Aqua 1',
                'Layout Aqua 2',
                'Layout Aqua 3',
                'Layout Aqua 4',
                'Layout Luxury 6',
            ],
        ];

        $now = now();

        foreach ($groups as $categoryName => $names) {
            $categoryId = DB::table('floor_plan_categories')->where('name', $categoryName)->value('id');
            if (!$categoryId) {
                continue;
            }

            foreach ($names as $index => $name) {
                $slug = $this->slugFor($name, $categoryName);
                $sort = $index + 1;
                $existing = DB::table('floor_plans')->where('slug', $slug)->first();
                if ($existing) {
                    DB::table('floor_plans')->where('id', $existing->id)->update([
                        'category_id' => $categoryId,
                        'sort' => $sort,
                        'status' => 1,
                        'updated_at' => $now,
                    ]);
                    continue;
                }

                $overview = $name === 'Mặt bằng tổng quan';
                DB::table('floor_plans')->insert([
                    'category_id' => $categoryId,
                    'name' => $name,
                    'slug' => $slug,
                    'eyebrow' => 'MẶT BẰNG TÒA NHÀ',
                    'title' => $name.' - '.$categoryName,
                    'intro' => $overview
                        ? 'Mặt bằng tổng quan '.$categoryName.', vị trí các tòa và tiện ích nội khu.'
                        : 'Thông tin '.$name.' tại '.$categoryName.', layout tòa nhà và layout từng căn hộ.',
                    'content' => $overview
                        ? $this->overviewHtml($categoryName)
                        : $this->layoutHtml($name, $categoryName),
                    'journal' => json_encode($this->journal($name), JSON_UNESCAPED_UNICODE),
                    'sort' => $sort,
                    'status' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        DB::table('floor_plans')
            ->where('slug', 'layout-landmark-3-vinhomes-central-park')
            ->update(['sort' => 99, 'updated_at' => $now]);
    }

    public function down()
    {
        $slugs = [
            'mat-bang-tong-quan-vinhomes-central-park',
            'layout-central-1',
            'layout-central-2',
            'layout-central-3',
            'layout-landmark-1',
            'layout-landmark-2',
            'layout-landmark-4',
            'layout-landmark-5',
            'layout-landmark-6',
            'layout-landmark-plus',
            'layout-landmark-81',
            'layout-park-1',
            'layout-park-2',
            'layout-park-3',
            'layout-park-4',
            'layout-park-5',
            'layout-park-6',
            'layout-park-7',
            'mat-bang-tong-quan-vinhomes-golden-river',
            'layout-aqua-1',
            'layout-aqua-2',
            'layout-aqua-3',
            'layout-aqua-4',
            'layout-luxury-6',
        ];

        DB::table('floor_plans')->whereIn('slug', $slugs)->delete();
    }

    private function slugFor($name, $categoryName)
    {
        $slug = to_slug($name);
        if ($name === 'Mặt bằng tổng quan') {
            $slug .= '-'.to_slug($categoryName);
        }

        return $slug;
    }

    private function overviewHtml($project)
    {
        $project = e($project);

        return '<h2>Mặt bằng tổng quan '.$project.'</h2>'
            .'<ul>'
            .'<li>Sơ đồ vị trí các tòa trong dự án</li>'
            .'<li>Cổng vào và trục đường nội khu</li>'
            .'<li>Phân khu căn hộ, tiện ích và công viên</li>'
            .'</ul>'
            .'<p>[[nhat-ky]]</p>';
    }

    private function layoutHtml($name, $project)
    {
        $name = e($name);
        $project = e($project);

        return '<table class="fp-overview"><tbody><tr>'
            .'<td><img src="" alt="'.$name.'" /><p>'.$name.'</p></td>'
            .'<td><h2>'.$name.'</h2><ul>'
            .'<li>Số tầng:</li>'
            .'<li>Số lượng căn hộ/sàn:</li>'
            .'<li>Số lượng thang máy/toà:</li>'
            .'<li>Chiều rộng hành lang:</li>'
            .'</ul><p>Dự án: '.$project.'</p></td>'
            .'</tr></tbody></table>'
            .'<p>[[nhat-ky]]</p>'
            .'<h2>Mặt bằng các tầng</h2>'
            .'<table class="fp-layout"><tbody><tr>'
            .'<td><img src="" alt="'.$name.'" /><p>'.$name.'</p></td>'
            .'<td><h3>Thông tin '.$name.'</h3>'
            .'<p>Số phòng ngủ, số vệ sinh</p>'
            .'<p>Diện tích tim tường:</p>'
            .'<p>Diện tích thông thủy:</p>'
            .'<p>Hướng cửa:</p>'
            .'<p>Hướng ban công:</p>'
            .'</td></tr></tbody></table>';
    }

    private function journal($name)
    {
        $label = trim(preg_replace('/^layout\s+/i', '', $name));

        return [
            [
                'dow' => 'Thứ Tư',
                'date' => '07/10/2026',
                'time' => '07:15',
                'sale' => [$label.' – 2PN – 8.5 Tỷ – Sổ hồng'],
                'rent' => [$label.' – 1PN – 21 Triệu – Full nội thất'],
            ],
            [
                'dow' => 'Thứ Ba',
                'date' => '06/10/2026',
                'time' => '07:15',
                'sale' => [$label.' – 3PN – 12 Tỷ – View sông'],
                'rent' => [],
            ],
        ];
    }
}
