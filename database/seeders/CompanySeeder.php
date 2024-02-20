<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            [
                'name_ar' => 'مسك الدار',
                'name_en' => 'مسك الدار',
            ],
            [
                'name_ar' => 'الاساس العربية',
                'name_en' => 'الاساس العربية',
            ],
            [
                'name_ar' => 'تايم كليك  للتجارة العامة ',
                'name_en' => 'تايم كليك  للتجارة العامة ',
            ],
            [
                'name_ar' => 'انترناشونال ستار',
                'name_en' => 'انترناشونال ستار',
            ],
            [
                'name_ar' => 'تايم كليك العقارية ',
                'name_en' => 'تايم كليك العقارية ',
            ],
            [
                'name_ar' => 'المسك للمواد الغذائيه ',
                'name_en' => 'المسك للمواد الغذائيه ',
            ],
            [
                'name_ar' => 'ام اس ام ',
                'name_en' => 'ام اس ام ',
            ],
            [
                'name_ar' => 'تايم كليك',
                'name_en' => 'تايم كليك',
            ],
        ];


        foreach ($companies as $company) {
            Company::create($company);
        }
    }
}
