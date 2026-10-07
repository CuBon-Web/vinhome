<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SeedMuaTypeTwoInventoryLogs extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE product_type_two MODIFY content LONGTEXT NULL');

        $path = database_path('data/mua_inventory_logs.json');
        $logs = json_decode(file_get_contents($path), true);
        $logs['tat-ca-phong-ngu'] = [
            'price_note' => 'Giá từ 5,8 tỷ',
            'badge' => 'Tổng hợp',
            'logs' => [
                [
                    'dow' => 'Thứ Ba',
                    'date' => '06/10/2026',
                    'title' => 'Giỏ hàng tổng hợp Vinhomes Central Park – Thứ Ba 06/10/2026',
                    'time' => '07:00',
                    'groups' => [
                        [
                            'name' => 'Khu Landmark',
                            'items' => [
                                ['label' => 'Landmark 4', 'text' => '1PN – 6 Tỷ – Sổ hồng – 50.5 m2 – Fullnt'],
                                ['label' => 'Landmark 81', 'text' => '2PN – 12.5 Tỷ – Sổ hồng – View sông'],
                            ],
                        ],
                        [
                            'name' => 'Khu Park',
                            'items' => [
                                ['label' => 'Park 1', 'text' => '1PN – 6 Tỷ – Sổ hồng – Fullnt'],
                            ],
                        ],
                    ],
                ],
            ],
        ];
        $logs['tin-rao-ban'] = [
            'price_note' => 'Tin đăng mới trong ngày',
            'badge' => 'Mới',
            'logs' => [
                [
                    'dow' => 'Thứ Ba',
                    'date' => '06/10/2026',
                    'title' => 'Tin rao bán Vinhomes Central Park – Thứ Ba 06/10/2026',
                    'time' => '08:30',
                    'groups' => [
                        [
                            'name' => 'Tin mới',
                            'items' => [
                                ['label' => 'Landmark Plus', 'text' => '1PN – 7 Tỷ – Fullnt – View thành phố'],
                                ['label' => 'Central 2', 'text' => '2PN – 9.2 Tỷ – Sổ hồng – Đang trống'],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $muaId = DB::table('product_category')->where('slug', 'mua')->value('id');
        if (!$muaId) {
            return;
        }

        foreach ($logs as $slug => $payload) {
            DB::table('product_type_two')
                ->where('cate_id', $muaId)
                ->where('slug', $slug)
                ->update([
                    'content' => json_encode($payload, JSON_UNESCAPED_UNICODE),
                    'updated_at' => now(),
                ]);
        }
    }

    public function down()
    {
        $muaId = DB::table('product_category')->where('slug', 'mua')->value('id');
        if ($muaId) {
            DB::table('product_type_two')->where('cate_id', $muaId)->update(['content' => null]);
        }

        DB::statement('ALTER TABLE product_type_two MODIFY content TEXT NULL');
    }
}
