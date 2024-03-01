<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'id' => '1',
            'logo' => NULL,
            'favicon' => NULL,
            'phone' => '22094040',
            'fax' => '22094040',
            'address_ar' => 'الضجيج',
            'address_en' => 'Dajeej',
            'knet_tax' => '0.070',
            'cash_account_id' => '53',
            'bank_account_id' => '57',
            'bank_charges_account_id' => '366',
            'receivables_account_id' => '92',
            'internal_parts_account_id' => '91',
            'created_at' => '2024-03-01 15:48:39',
            'updated_at' => '2024-03-01 15:53:47',
        ];

        Setting::updateOrCreate(['id' => $settings['id']],$settings);
    }
}
