<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            ['id' => '1', 'name_ar' => 'تم انشاء الطلب', 'name_en' => 'Created', 'color' => '#3399ff', 'created_at' => null, 'updated_at' => '2022-01-06 20:28:23', 'deleted_at' => null],
            ['id' => '2', 'name_ar' => 'تم التوزيع', 'name_en' => 'Destributed', 'color' => '#9b082d', 'created_at' => null, 'updated_at' => '2022-01-09 18:47:08', 'deleted_at' => null],
            ['id' => '3', 'name_ar' => 'تم استلام الطلب', 'name_en' => 'Received', 'color' => '#d6b300', 'created_at' => null, 'updated_at' => '2021-08-14 02:03:23', 'deleted_at' => null],
            ['id' => '4', 'name_ar' => 'منفذ', 'name_en' => 'Completed', 'color' => '#2eb85c', 'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' => '5', 'name_ar' => 'توقف مؤقت', 'name_en' => 'On Hold', 'color' => '#636f83', 'created_at' => null, 'updated_at' => '2021-08-14 02:06:40', 'deleted_at' => null],
            ['id' => '6', 'name_ar' => 'ملغي', 'name_en' => 'Cancelled', 'color' => '#e55353', 'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
            ['id' => '7', 'name_ar' => 'وصول', 'name_en' => 'Arrived', 'color' => '#e356e6', 'created_at' => null, 'updated_at' => null, 'deleted_at' => null],
        ];
        Status::insert($statuses);
    }
}
