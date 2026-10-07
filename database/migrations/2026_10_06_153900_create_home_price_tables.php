<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateHomePriceTables extends Migration
{
    public function up()
    {
        Schema::create('home_price_meta', function (Blueprint $table) {
            $table->id();
            $table->string('eyebrow')->default('');
            $table->string('title')->default('');
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('home_price_items', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('');
            $table->string('area')->default('');
            $table->string('price')->default('');
            $table->unsignedSmallInteger('sort')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        $now = now();
        DB::table('home_price_meta')->insert([
            'eyebrow' => 'Bảng giá tham khảo · T9/2026',
            'title' => 'Bảng giá bán căn hộ Vinhomes Central Park',
            'note' => '* Giá bán bao gồm thuế thu nhập cá nhân 2%, phí môi giới 1%, phí công chứng và không bao gồm phí trước bạ 0,5%.',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $rows = [
            ['1 phòng ngủ', 'Diện tích 36 – 56m²', '6 – 8 tỷ'],
            ['2 phòng ngủ', 'Diện tích 68 – 92m²', '8 – 14 tỷ'],
            ['3 phòng ngủ', 'Diện tích 95 – 140m²', '13 – 20 tỷ'],
            ['4 phòng ngủ', 'Diện tích 142 – 188m²', '17 – 30 tỷ'],
            ['Penthouse / Shophouse', 'Diện tích 180 – 450m²', 'từ 20 tỷ'],
            ['Biệt thự (Villas)', 'Diện tích 240 – 500m²', 'trên 150 tỷ'],
        ];
        foreach ($rows as $index => $row) {
            DB::table('home_price_items')->insert([
                'type' => $row[0],
                'area' => $row[1],
                'price' => $row[2],
                'sort' => $index + 1,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('home_price_items');
        Schema::dropIfExists('home_price_meta');
    }
}
