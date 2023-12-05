<?php

namespace Database\Seeders;

use App\Models\CarBrand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'name_en' => 'تويوتا',
                'name_ar' => 'تويوتا',
            ],
            [
                'name_en' => 'سوزوكي',
                'name_ar' => 'سوزوكي',
            ],
            [
                'name_en' => 'ميتشوبيشي',
                'name_ar' => 'ميتشوبيشي',
            ],
            [
                'name_en' => 'هيونداى',
                'name_ar' => 'هيونداى',
            ],
            [
                'name_en' => 'شيفروليه',
                'name_ar' => 'شيفروليه',
            ],
            [
                'name_en' => 'هيونداى h1',
                'name_ar' => 'هيونداى h1',
            ],
            [
                'name_en' => 'سوزوكي سويفت',
                'name_ar' => 'سوزوكي سويفت',
            ],
            [
                'name_en' => 'مرسيدس',
                'name_ar' => 'مرسيدس',
            ],
            [
                'name_en' => 'كاتربلر',
                'name_ar' => 'كاتربلر',
            ],
            [
                'name_en' => 'هيتاشي',
                'name_ar' => 'هيتاشي',
            ],
            [
                'name_en' => 'هينو ديترو',
                'name_ar' => 'هينو ديترو',
            ],
            [
                'name_en' => 'نيسان',
                'name_ar' => 'نيسان',
            ],
            [
                'name_en' => 'جيب',
                'name_ar' => 'جيب',
            ],
            [
                'name_en' => 'جى ام سى',
                'name_ar' => 'جى ام سى',
            ],
        ];
        foreach ($brands as $brand) {
            CarBrand::create($brand);
        }    }
}
