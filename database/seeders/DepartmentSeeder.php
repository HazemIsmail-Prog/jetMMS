<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = array(
            array('id' => '1', 'name_ar' => 'الادارة', 'name_en' => 'Management', 'active' => '1', 'is_service' => '0', 'created_at' => NULL, 'updated_at' => NULL, 'deleted_at' => NULL),
            array('id' => '2', 'name_ar' => 'تكييف - ثلاجات - غسالات', 'name_en' => 'Air Conditioning - Refrigerator - Washing Macines', 'active' => '1', 'is_service' => '1', 'created_at' => NULL, 'updated_at' => '2023-07-16 18:24:55', 'deleted_at' => NULL),
            array('id' => '3', 'name_ar' => 'صحي', 'name_en' => 'Plumbing', 'active' => '1', 'is_service' => '1', 'created_at' => NULL, 'updated_at' => '2023-07-13 21:24:12', 'deleted_at' => NULL),
            array('id' => '4', 'name_ar' => 'كهرباء', 'name_en' => 'Electrical', 'active' => '1', 'is_service' => '1', 'created_at' => NULL, 'updated_at' => '2023-07-13 21:24:25', 'deleted_at' => NULL),
            array('id' => '5', 'name_ar' => 'الومنيوم ونجارة', 'name_en' => 'Aluminum & Carpentry', 'active' => '1', 'is_service' => '1', 'created_at' => NULL, 'updated_at' => NULL, 'deleted_at' => NULL),
            array('id' => '6', 'name_ar' => 'كاميرات - ساتلايت - الكترونيات', 'name_en' => 'Cameras - Satellite - Electronics', 'active' => '1', 'is_service' => '1', 'created_at' => NULL, 'updated_at' => '2023-07-16 18:23:07', 'deleted_at' => NULL),
            array('id' => '8', 'name_ar' => 'صبغ', 'name_en' => 'Painting', 'active' => '1', 'is_service' => '1', 'created_at' => NULL, 'updated_at' => NULL, 'deleted_at' => NULL),
            array('id' => '9', 'name_ar' => 'انشائي', 'name_en' => 'Construction', 'active' => '1', 'is_service' => '1', 'created_at' => NULL, 'updated_at' => '2023-07-16 18:21:37', 'deleted_at' => NULL),
            array('id' => '16', 'name_ar' => 'استفسار', 'name_en' => 'Information', 'active' => '1', 'is_service' => '0', 'created_at' => '2023-07-13 21:30:01', 'updated_at' => '2023-10-20 16:40:30', 'deleted_at' => NULL),
            array('id' => '17', 'name_ar' => 'خدمة غير متوفرة', 'name_en' => 'Not Available Service', 'active' => '1', 'is_service' => '0', 'created_at' => '2023-07-13 21:30:32', 'updated_at' => '2023-10-20 16:40:24', 'deleted_at' => NULL),
            array('id' => '18', 'name_ar' => 'العمليات', 'name_en' => 'العمليات', 'active' => '1', 'is_service' => '0', 'created_at' => '2023-11-19 17:36:44', 'updated_at' => '2023-11-19 17:36:44', 'deleted_at' => NULL),
            array('id' => '19', 'name_ar' => 'المبيعات', 'name_en' => 'المبيعات', 'active' => '1', 'is_service' => '0', 'created_at' => '2023-11-19 17:37:08', 'updated_at' => '2023-11-19 17:37:08', 'deleted_at' => NULL),
            array('id' => '20', 'name_ar' => 'تقنية المعلومات', 'name_en' => 'تقنية المعلومات', 'active' => '1', 'is_service' => '0', 'created_at' => '2023-11-19 17:37:18', 'updated_at' => '2023-11-19 17:37:18', 'deleted_at' => NULL),
            array('id' => '21', 'name_ar' => 'شؤون الموظفين', 'name_en' => 'شؤون الموظفين', 'active' => '1', 'is_service' => '0', 'created_at' => '2023-11-19 17:38:56', 'updated_at' => '2023-11-19 17:38:56', 'deleted_at' => NULL),
            array('id' => '22', 'name_ar' => 'المالية', 'name_en' => 'المالية', 'active' => '1', 'is_service' => '0', 'created_at' => '2023-11-19 17:39:05', 'updated_at' => '2023-11-19 17:39:05', 'deleted_at' => NULL),
            array('id' => '23', 'name_ar' => 'العقود السنوية', 'name_en' => 'العقود السنوية', 'active' => '1', 'is_service' => '0', 'created_at' => '2023-11-19 17:39:13', 'updated_at' => '2023-11-19 17:39:13', 'deleted_at' => NULL),
            array('id' => '24', 'name_ar' => 'غير محدد', 'name_en' => 'غير محدد', 'active' => '1', 'is_service' => '0', 'created_at' => '2023-11-19 17:39:22', 'updated_at' => '2023-11-19 17:39:22', 'deleted_at' => NULL),
            array('id' => '25', 'name_ar' => 'خارجي', 'name_en' => 'خارجي', 'active' => '1', 'is_service' => '0', 'created_at' => '2023-11-19 17:39:35', 'updated_at' => '2023-11-19 17:39:35', 'deleted_at' => NULL)
        );
        Department::insert($departments);    }
}
