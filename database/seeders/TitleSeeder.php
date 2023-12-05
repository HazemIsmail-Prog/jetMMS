<?php

namespace Database\Seeders;

use App\Models\Title;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $titles = array(
            array('id' => '1', 'name_ar' => 'مدير التسويق و المبيعات', 'name_en' => 'مدير التسويق و المبيعات', 'active' => '1', 'created_at' => '2021-06-18 17:47:38', 'updated_at' => '2023-11-19 17:18:25', 'deleted_at' => NULL),
            array('id' => '2', 'name_ar' => 'مهندس نظم', 'name_en' => 'IT', 'active' => '1', 'created_at' => '2021-06-18 17:48:15', 'updated_at' => '2023-11-19 17:18:39', 'deleted_at' => NULL),
            array('id' => '3', 'name_ar' => 'شؤون الموظفين', 'name_en' => 'HR', 'active' => '1', 'created_at' => '2021-06-18 17:49:02', 'updated_at' => '2023-11-19 17:18:57', 'deleted_at' => NULL),
            array('id' => '4', 'name_ar' => 'مدير مالي', 'name_en' => 'Financial Manager', 'active' => '1', 'created_at' => '2021-06-18 17:49:15', 'updated_at' => '2021-06-18 17:49:15', 'deleted_at' => NULL),
            array('id' => '5', 'name_ar' => 'محاسب', 'name_en' => 'Accountant', 'active' => '1', 'created_at' => '2021-06-18 17:49:26', 'updated_at' => '2021-06-18 17:49:26', 'deleted_at' => NULL),
            array('id' => '6', 'name_ar' => 'مسؤول شفت', 'name_en' => 'Dispatcher', 'active' => '1', 'created_at' => '2021-06-18 17:51:30', 'updated_at' => '2023-11-19 17:19:12', 'deleted_at' => NULL),
            array('id' => '7', 'name_ar' => 'مسؤول سيارات', 'name_en' => 'Cars Supervisor', 'active' => '1', 'created_at' => '2021-06-18 17:51:48', 'updated_at' => '2021-06-18 17:51:48', 'deleted_at' => NULL),
            array('id' => '8', 'name_ar' => 'مسؤول تحصيل', 'name_en' => 'Cashier', 'active' => '1', 'created_at' => '2021-06-18 17:51:57', 'updated_at' => '2023-11-19 17:19:35', 'deleted_at' => NULL),
            array('id' => '9', 'name_ar' => 'مندوب عام', 'name_en' => 'مندوب عام', 'active' => '1', 'created_at' => '2021-06-18 17:52:11', 'updated_at' => '2023-11-19 17:19:47', 'deleted_at' => NULL),
            array('id' => '10', 'name_ar' => 'مراقب', 'name_en' => 'Foreman', 'active' => '1', 'created_at' => '2021-06-18 17:52:23', 'updated_at' => '2021-06-18 17:52:23', 'deleted_at' => NULL),
            array('id' => '11', 'name_ar' => 'فني', 'name_en' => 'Technician', 'active' => '1', 'created_at' => '2021-06-18 17:52:33', 'updated_at' => '2021-06-18 17:52:33', 'deleted_at' => NULL),
            array('id' => '12', 'name_ar' => 'خدمة عملاء', 'name_en' => 'Call Center', 'active' => '1', 'created_at' => '2021-06-18 18:02:13', 'updated_at' => '2023-11-19 17:20:16', 'deleted_at' => NULL),
            array('id' => '13', 'name_ar' => 'مقاول', 'name_en' => 'Sub Contractor', 'active' => '1', 'created_at' => NULL, 'updated_at' => NULL, 'deleted_at' => NULL),
            array('id' => '14', 'name_ar' => 'مسؤول عقود', 'name_en' => 'Contracts Manager', 'active' => '1', 'created_at' => '2021-06-19 02:34:17', 'updated_at' => '2023-11-19 17:20:36', 'deleted_at' => NULL),
            array('id' => '15', 'name_ar' => 'سائق', 'name_en' => 'سائق', 'active' => '1', 'created_at' => '2023-11-19 17:20:49', 'updated_at' => '2023-11-19 17:20:49', 'deleted_at' => NULL),
            array('id' => '16', 'name_ar' => 'مساعد', 'name_en' => 'مساعد', 'active' => '1', 'created_at' => '2023-11-19 17:21:00', 'updated_at' => '2023-11-19 17:21:05', 'deleted_at' => NULL),
            array('id' => '17', 'name_ar' => 'مسؤول ترميمات', 'name_en' => 'مسؤول ترميمات', 'active' => '1', 'created_at' => '2023-11-19 17:21:22', 'updated_at' => '2023-11-19 17:21:22', 'deleted_at' => NULL),
            array('id' => '18', 'name_ar' => 'مسؤول مبيعات', 'name_en' => 'مسؤول مبيعات', 'active' => '1', 'created_at' => '2023-11-19 17:21:38', 'updated_at' => '2023-11-19 17:25:37', 'deleted_at' => NULL),
            array('id' => '19', 'name_ar' => 'مهندس مدني', 'name_en' => 'مهندس مدني', 'active' => '1', 'created_at' => '2023-11-19 17:21:49', 'updated_at' => '2023-11-19 17:25:47', 'deleted_at' => NULL),
            array('id' => '20', 'name_ar' => 'مراقب انشائي', 'name_en' => 'مراقب انشائي', 'active' => '1', 'created_at' => '2023-11-19 17:21:57', 'updated_at' => '2023-11-19 17:26:06', 'deleted_at' => NULL),
            array('id' => '21', 'name_ar' => 'مسؤول صيانة', 'name_en' => 'مسؤول صيانة', 'active' => '1', 'created_at' => '2023-11-19 17:22:06', 'updated_at' => '2023-11-19 17:26:14', 'deleted_at' => NULL),
            array('id' => '22', 'name_ar' => 'مسؤول ملفات', 'name_en' => 'مسؤول ملفات', 'active' => '1', 'created_at' => '2023-11-19 17:25:07', 'updated_at' => '2023-11-19 17:26:25', 'deleted_at' => NULL),
            array('id' => '23', 'name_ar' => 'عامل بوفيه', 'name_en' => 'عامل بوفيه', 'active' => '1', 'created_at' => '2023-11-19 17:25:12', 'updated_at' => '2023-11-19 17:26:34', 'deleted_at' => NULL),
            array('id' => '24', 'name_ar' => 'مدير تنفيذي', 'name_en' => 'مدير تنفيذي', 'active' => '1', 'created_at' => '2023-11-19 17:25:18', 'updated_at' => '2023-11-19 17:26:51', 'deleted_at' => NULL),
            array('id' => '25', 'name_ar' => 'خارجي', 'name_en' => 'خارجي', 'active' => '1', 'created_at' => '2023-11-19 17:25:25', 'updated_at' => '2023-11-19 17:26:59', 'deleted_at' => NULL)
        );

        Title::insert($titles);    }
}
