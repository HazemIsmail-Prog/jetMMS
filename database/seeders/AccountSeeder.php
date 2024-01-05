<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            [
                'name_en' => 'Assets',
                'name_ar' => 'الاصول',
                'account_id' => null,
                'usage' => '',
                'level' => 0,
                'index' => 0,
                'active' => 1,
            ],
            [
                'name_en' => 'Income',
                'name_ar' => 'الدخل',
                'account_id' => null,
                'usage' => '',
                'level' => 0,
                'index' => 1,
                'active' => 1,
            ],
            [
                'name_en' => 'Expenses',
                'name_ar' => 'المصروفات',
                'account_id' => null,
                'usage' => '',
                'level' => 0,
                'index' => 2,
                'active' => 1,
            ],
            [
                'name_en' => 'Liabilities',
                'name_ar' => 'الالتزامات',
                'account_id' => null,
                'usage' => '',
                'level' => 0,
                'index' => 3,
                'active' => 1,
            ],
            [
                'name_en' => 'Equity',
                'name_ar' => 'حقوق الملكية',
                'account_id' => null,
                'usage' => '',
                'level' => 0,
                'index' => 4,
                'active' => 1,
            ],
        ];

        Account::insert($accounts);
    }
}
