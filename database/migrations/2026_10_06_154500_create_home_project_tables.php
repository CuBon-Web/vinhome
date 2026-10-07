<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateHomeProjectTables extends Migration
{
    public function up()
    {
        Schema::create('home_project_meta', function (Blueprint $table) {
            $table->id();
            $table->string('eyebrow')->default('');
            $table->string('title')->default('');
            $table->text('paragraph_1')->nullable();
            $table->text('paragraph_2')->nullable();
            $table->text('image')->nullable();
            $table->string('image_alt')->default('');
            $table->string('caption')->default('');
            $table->timestamps();
        });

        Schema::create('home_project_specs', function (Blueprint $table) {
            $table->id();
            $table->string('label')->default('');
            $table->string('value')->default('');
            $table->unsignedSmallInteger('sort')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        $now = now();
        DB::table('home_project_meta')->insert([
            'eyebrow' => 'Khám phá dự án',
            'title' => 'Biểu tượng mới của Thành phố Hồ Chí Minh',
            'paragraph_1' => 'Vinhomes Central Park là dự án bất động sản hàng đầu tại trái tim TP.HCM, được phát triển bởi <strong>Tập đoàn Vingroup</strong>. Nằm tại vị trí đắc địa ven sông Sài Gòn, quận Bình Thạnh, gần trung tâm thành phố.',
            'paragraph_2' => 'Đây là địa điểm lý tưởng để mua căn hộ Vinhomes Central Park, mở ra cơ hội không giới hạn để trải nghiệm cuộc sống đô thị hiện đại và tiện nghi bậc nhất.',
            'image' => 'https://bestvinhomes.com/wp-content/uploads/2023/05/31911193928_a4f5fc9a4c_k.jpg',
            'image_alt' => 'Toàn cảnh Vinhomes Central Park',
            'caption' => 'Toàn cảnh dự án Vinhomes Central Park',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $rows = [
            ['Dự án', 'Vinhomes Central Park'],
            ['Chủ đầu tư', 'Tập đoàn Vingroup'],
            ['Tổng diện tích', '43,91 ha'],
            ['Mật độ xây dựng', '18,6%'],
            ['Địa chỉ', '720A Điện Biên Phủ, Thạnh Mỹ Tây, HCM'],
            ['Quy mô', '18 tòa & 88 biệt thự'],
            ['Loại hình', 'Căn hộ, Penthouse, Sky Villa, Biệt thự'],
            ['Số phòng ngủ', '1 – 2 – 3 – 4 PN'],
            ['Pháp lý', 'Đã có sổ hồng'],
            ['Khởi công – Bàn giao', '2014 – 2018'],
        ];
        foreach ($rows as $index => $row) {
            DB::table('home_project_specs')->insert([
                'label' => $row[0],
                'value' => $row[1],
                'sort' => $index + 1,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('home_project_specs');
        Schema::dropIfExists('home_project_meta');
    }
}
