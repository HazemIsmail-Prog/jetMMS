<?php

namespace Database\Seeders;

use App\Models\CarType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name_ar' => 'جيب',
                'name_en' => 'جيب',
            ],
            [
                'name_ar' => 'صالون',
                'name_en' => 'صالون',
            ],
            [
                'name_ar' => 'وانيت L 200',
                'name_en' => 'وانيت L 200',
            ],
            [
                'name_ar' => 'باص مقفل',
                'name_en' => 'باص مقفل',
            ],
            [
                'name_ar' => 'بوكس مقفل',
                'name_en' => 'بوكس مقفل',
            ],
            [
                'name_ar' => 'باص مقفل ركاب',
                'name_en' => 'باص مقفل ركاب',
            ],
            [
                'name_ar' => 'هاف لوري',
                'name_en' => 'هاف لوري',
            ],
            [
                'name_ar' => 'وانيت',
                'name_en' => 'وانيت',
            ],
        ];

        foreach ($types as $type) {
            CarType::create($type);
        }    }
}
