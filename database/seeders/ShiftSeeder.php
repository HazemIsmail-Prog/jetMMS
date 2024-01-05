<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shifts = array(
            array('id' => '1', 'name_ar' => 'الشفت الاول', 'name_en' => 'First Shift', 'start_time' => '07:30:00', 'end_time' => '18:30:00', 'created_at' => '2023-07-08 13:39:53', 'updated_at' => '2023-11-15 10:56:58'),
            array('id' => '2', 'name_ar' => 'الشفت الثاني', 'name_en' => 'Second Shift', 'start_time' => '10:30:00', 'end_time' => '21:30:00', 'created_at' => '2023-07-08 13:40:44', 'updated_at' => '2023-11-15 10:57:21'),
            array('id' => '3', 'name_ar' => 'الشفت الثالث', 'name_en' => 'Third Shift', 'start_time' => '13:30:00', 'end_time' => '00:30:00', 'created_at' => '2023-07-08 13:41:53', 'updated_at' => '2023-11-15 10:57:50'),
            array('id' => '4', 'name_ar' => 'الشفت الرابع', 'name_en' => 'Forth Shift', 'start_time' => '14:00:00', 'end_time' => '23:00:00', 'created_at' => '2023-07-08 13:42:31', 'updated_at' => '2023-07-08 13:42:31')
        );
        Shift::insert($shifts);

    }
}
