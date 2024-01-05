<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'name' => 'dashboard_menu',
                'section_name_ar' => 'لوحة التحكم',
                'section_name_en' => 'dashboard',
                'desc_ar' => 'عرض قائمة لوحة المعلومات',
                'desc_en' => 'Dashboard Menu',
            ],
            [
                'name' => 'settings_menu',
                'section_name_ar' => 'الاعدادات',
                'section_name_en' => 'settings',
                'desc_ar' => 'عرض قائمة الاعدادات',
                'desc_en' => 'Settings Menu',
            ],

            //roles
            [
                'name' => 'roles_menu',
                'section_name_ar' => 'الأدوار',
                'section_name_en' => 'roles',
                'desc_ar' => 'عرض قائمة الادوار',
                'desc_en' => 'Roles Menu',
            ],
            [
                'name' => 'roles_create',
                'section_name_ar' => 'الأدوار',
                'section_name_en' => 'roles',
                'desc_ar' => 'انشاء ادوار',
                'desc_en' => 'Roles Create',
            ],
            [
                'name' => 'roles_edit',
                'section_name_ar' => 'الأدوار',
                'section_name_en' => 'roles',
                'desc_ar' => 'تعديل الادوار',
                'desc_en' => 'Roles Edit',
            ],
            [
                'name' => 'roles_delete',
                'section_name_ar' => 'الأدوار',
                'section_name_en' => 'roles',
                'desc_ar' => 'حذف الادوار',
                'desc_en' => 'Roles Delete',
            ],

            //departments
            [
                'name' => 'departments_menu',
                'section_name_ar' => 'الأقسام',
                'section_name_en' => 'departments',
                'desc_ar' => 'عرض قائمة الاقسام',
                'desc_en' => 'Departments Menu',
            ],
            [
                'name' => 'departments_create',
                'section_name_ar' => 'الأقسام',
                'section_name_en' => 'departments',
                'desc_ar' => 'انشاء اقسام',
                'desc_en' => 'Departments Create',
            ],
            [
                'name' => 'departments_edit',
                'section_name_ar' => 'الأقسام',
                'section_name_en' => 'departments',
                'desc_ar' => 'تعديل الاقسام',
                'desc_en' => 'Departments Edit',
            ],
            [
                'name' => 'departments_delete',
                'section_name_ar' => 'الأقسام',
                'section_name_en' => 'departments',
                'desc_ar' => 'حذف الاقسام',
                'desc_en' => 'Departments Delete',
            ],

            //services
            [
                'name' => 'services_menu',
                'section_name_ar' => 'الخدمات',
                'section_name_en' => 'Services',
                'desc_ar' => 'عرض قائمة الخدمات',
                'desc_en' => 'Services Menu',
            ],
            [
                'name' => 'services_create',
                'section_name_ar' => 'الخدمات',
                'section_name_en' => 'Services',
                'desc_ar' => 'انشاء خدمات',
                'desc_en' => 'Services Create',
            ],
            [
                'name' => 'services_edit',
                'section_name_ar' => 'الخدمات',
                'section_name_en' => 'Services',
                'desc_ar' => 'تعديل الخدمات',
                'desc_en' => 'Services Edit',
            ],
            [
                'name' => 'services_delete',
                'section_name_ar' => 'الخدمات',
                'section_name_en' => 'Services',
                'desc_ar' => 'حذف الخدمات',
                'desc_en' => 'Services Delete',
            ],

            //titles
            [
                'name' => 'titles_menu',
                'section_name_ar' => 'الوظائف',
                'section_name_en' => 'titles',
                'desc_ar' => 'عرض قائمة الوظائف',
                'desc_en' => 'Titles Menu',
            ],
            [
                'name' => 'titles_create',
                'section_name_ar' => 'الوظائف',
                'section_name_en' => 'titles',
                'desc_ar' => 'انشاء وظائف',
                'desc_en' => 'Titles Create',
            ],
            [
                'name' => 'titles_edit',
                'section_name_ar' => 'الوظائف',
                'section_name_en' => 'titles',
                'desc_ar' => 'تعديل الوظائف',
                'desc_en' => 'Titles Edit',
            ],
            [
                'name' => 'titles_delete',
                'section_name_ar' => 'الوظائف',
                'section_name_en' => 'titles',
                'desc_ar' => 'حذف الوظائف',
                'desc_en' => 'Titles Delete',
            ],

            //users

            [
                'name' => 'users_menu',
                'section_name_ar' => 'المستخدمين',
                'section_name_en' => 'users',
                'desc_ar' => 'عرض قائمة المستخدمين',
                'desc_en' => 'Users Menu',
            ],
            [
                'name' => 'users_create',
                'section_name_ar' => 'المستخدمين',
                'section_name_en' => 'users',
                'desc_ar' => 'انشاء مستخدمين',
                'desc_en' => 'Users Create',
            ],
            [
                'name' => 'users_edit',
                'section_name_ar' => 'المستخدمين',
                'section_name_en' => 'users',
                'desc_ar' => 'تعديل بيانات المستخدمين',
                'desc_en' => 'Users Edit',
            ],
            [
                'name' => 'users_delete',
                'section_name_ar' => 'المستخدمين',
                'section_name_en' => 'users',
                'desc_ar' => 'حذف المستخدمين',
                'desc_en' => 'Users Delete',
            ],

            //statuses

            [
                'name' => 'statuses_menu',
                'section_name_ar' => 'الحالات',
                'section_name_en' => 'statuses',
                'desc_ar' => 'عرض قائمة الحالات',
                'desc_en' => 'Statuses Menu',
            ],
            [
                'name' => 'statuses_edit',
                'section_name_ar' => 'الحالات',
                'section_name_en' => 'statuses',
                'desc_ar' => 'تعديل الحالات',
                'desc_en' => 'Statuses Edit',
            ],

            //areas

            [
                'name' => 'areas_menu',
                'section_name_ar' => 'المناطق',
                'section_name_en' => 'areas',
                'desc_ar' => 'عرض قائمة المناطق',
                'desc_en' => 'Areas Menu',
            ],
            [
                'name' => 'areas_create',
                'section_name_ar' => 'المناطق',
                'section_name_en' => 'areas',
                'desc_ar' => 'انشاء المناطق',
                'desc_en' => 'Areas Create',
            ],
            [
                'name' => 'areas_edit',
                'section_name_ar' => 'المناطق',
                'section_name_en' => 'areas',
                'desc_ar' => 'تعديل المناطق',
                'desc_en' => 'Areas Edit',
            ],
            [
                'name' => 'areas_delete',
                'section_name_ar' => 'المناطق',
                'section_name_en' => 'areas',
                'desc_ar' => 'حذف المناطق',
                'desc_en' => 'Areas Delete',
            ],

            //operations title
            [
                'name' => 'operations_menu',
                'section_name_ar' => 'العمليات',
                'section_name_en' => 'operations',
                'desc_ar' => 'عرض قائمة العمليات',
                'desc_en' => 'Operations Menu',
            ],

            //customers

            [
                'name' => 'customers_menu',
                'section_name_ar' => 'العملاء',
                'section_name_en' => 'customers',
                'desc_ar' => 'عرض قائمة العملاء',
                'desc_en' => 'Customers Menu',
            ],
            [
                'name' => 'customers_create',
                'section_name_ar' => 'العملاء',
                'section_name_en' => 'customers',
                'desc_ar' => 'انشاء عميل جديد',
                'desc_en' => 'Customers Create',
            ],
            [
                'name' => 'customers_edit',
                'section_name_ar' => 'العملاء',
                'section_name_en' => 'customers',
                'desc_ar' => 'تعديل بيانات العملاء',
                'desc_en' => 'Customers Edit',
            ],
            [
                'name' => 'customers_delete',
                'section_name_ar' => 'العملاء',
                'section_name_en' => 'customers',
                'desc_ar' => 'حذف العملاء',
                'desc_en' => 'Customers Delete',
            ],

            //orders

            [
                'name' => 'orders_menu',
                'section_name_ar' => 'الطلبات',
                'section_name_en' => 'orders',
                'desc_ar' => 'عرض قائمة الطلبات',
                'desc_en' => 'Orders Menu',
            ],
            [
                'name' => 'orders_create',
                'section_name_ar' => 'الطلبات',
                'section_name_en' => 'orders',
                'desc_ar' => 'انشاء طلبات',
                'desc_en' => 'Orders Create',
            ],
            [
                'name' => 'orders_edit',
                'section_name_ar' => 'الطلبات',
                'section_name_en' => 'orders',
                'desc_ar' => 'تعديل الطلبات',
                'desc_en' => 'Orders Edit',
            ],
            [
                'name' => 'orders_show',
                'section_name_ar' => 'الطلبات',
                'section_name_en' => 'orders',
                'desc_ar' => 'عرض تفاصيل الطلبات',
                'desc_en' => 'Orders Show',
            ],

            //Invoices
            [
                'name' => 'invoices_menu',
                'section_name_ar' => 'الفواتير',
                'section_name_en' => 'Invoices',
                'desc_ar' => 'عرض قائمة الفواتير',
                'desc_en' => 'Invoices Menu',
            ],

            //dispatching

            [
                'name' => 'dispatching_menu',
                'section_name_ar' => 'التوزيع',
                'section_name_en' => 'dispatching',
                'desc_ar' => 'عرض قائمة التوزيع',
                'desc_en' => 'Dispatching Menu',
            ],

            //reports
            [
                'name' => 'reports_menu',
                'section_name_ar' => 'التقارير',
                'section_name_en' => 'reports',
                'desc_ar' => 'عرض قائمة التقارير',
                'desc_en' => 'Reports Menu',
            ],

            //shifts

            [
                'name' => 'shifts_menu',
                'section_name_ar' => 'الشيفتات',
                'section_name_en' => 'shifts',
                'desc_ar' => 'عرض قائمة الشيفتات',
                'desc_en' => 'Shifts Menu',
            ],
            [
                'name' => 'shifts_create',
                'section_name_ar' => 'الشيفتات',
                'section_name_en' => 'shifts',
                'desc_ar' => 'انشاء الشيفتات',
                'desc_en' => 'Shifts Create',
            ],
            [
                'name' => 'shifts_edit',
                'section_name_ar' => 'الشيفتات',
                'section_name_en' => 'shifts',
                'desc_ar' => 'تعديل الشيفتات',
                'desc_en' => 'Shifts Edit',
            ],
            [
                'name' => 'shifts_delete',
                'section_name_ar' => 'الشيفتات',
                'section_name_en' => 'shifts',
                'desc_ar' => 'حذف الشيفتات',
                'desc_en' => 'Shifts Delete',
            ],

            //marketing

            [
                'name' => 'marketing_menu',
                'section_name_ar' => 'التسويق',
                'section_name_en' => 'marketing',
                'desc_ar' => 'عرض قائمة التسويق',
                'desc_en' => 'Marketing Menu',
            ],
            [
                'name' => 'marketing_create',
                'section_name_ar' => 'التسويق',
                'section_name_en' => 'marketing',
                'desc_ar' => 'انشاء التسويق',
                'desc_en' => 'Marketing Create',
            ],
            [
                'name' => 'marketing_edit',
                'section_name_ar' => 'التسويق',
                'section_name_en' => 'marketing',
                'desc_ar' => 'تعديل التسويق',
                'desc_en' => 'Marketing Edit',
            ],
            [
                'name' => 'marketing_delete',
                'section_name_ar' => 'التسويق',
                'section_name_en' => 'marketing',
                'desc_ar' => 'حذف التسويق',
                'desc_en' => 'Marketing Delete',
            ],

        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                [
                    'name' => $permission['name'],
                ],
                [
                    'section_name_ar' => $permission['section_name_ar'],
                    'section_name_en' => $permission['section_name_en'],
                    'desc_ar' => $permission['desc_ar'],
                    'desc_en' => $permission['desc_en'],

                ]
            );
        }
        Role::find(1)->permissions()->attach(Permission::pluck('id'));
        
        //Run the following code to seed only permissions seeder
        //php artisan db:seed --class=PermissionSeeder

    }
}
