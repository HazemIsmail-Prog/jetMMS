<?php

namespace Database\Seeders;

use App\Models\CostCenter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CostCenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cost_centers = [
            [
                'id' => 1,
                'name_ar' => 'صيانة',
                'name_en' => 'Services',
            ],
            [
                'id' => 2,
                'name_ar' => 'بضاعة',
                'name_en' => 'Parts',
            ],
            [
                'id' => 3,
                'name_ar' => 'توصيل',
                'name_en' => 'Delivery',
            ],
        ];


        foreach ($cost_centers as $cost_center) {
            CostCenter::create($cost_center);
        }
    }
}
