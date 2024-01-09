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
            // Dashboard
            ['name' => 'dashboard_menu',        'section_name_ar' => 'لوحة التحكم',     'section_name_en' => 'dashboard',           'desc_ar' => 'عرض قائمة لوحة المعلومات',                'desc_en' => 'Dashboard Menu'],

            // Operations

            // Customers
            ['name' => 'customers_menu',        'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',          'desc_ar' => 'عرض قائمة العملاء',         'desc_en' => 'Create Customers'],
            ['name' => 'customers_create',      'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',          'desc_ar' => 'اضافة عملاء',               'desc_en' => 'Create Customers'],
            ['name' => 'customers_edit',        'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',          'desc_ar' => 'تعديل عملاء',               'desc_en' => 'Edit Customers'],
            ['name' => 'customers_delete',      'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',          'desc_ar' => 'حذف عملاء',               'desc_en' => 'Delete Customers'],
            
            ['name' => 'orders_menu',           'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',          'desc_ar' => 'عرض قائمة الطلبات',               'desc_en' => 'Orders Menu'],
            ['name' => 'orders_create',         'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',          'desc_ar' => 'انشاء الطلبات',               'desc_en' => 'Create Orders'],
            ['name' => 'orders_edit',           'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',          'desc_ar' => 'تعديل الطلبات',               'desc_en' => 'Edit Orders'],
            ['name' => 'marketing_menu',        'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',          'desc_ar' => 'عرض قائمة التسويق',               'desc_en' => 'Marketing Menu'],
            ['name' => 'dispatching_menu',      'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',          'desc_ar' => 'عرض قائمة التوزيع',               'desc_en' => 'Dispatching Menu'],


            // Accounting
            ['name' => 'accounts_menu',         'section_name_ar' => 'الحسابات',        'section_name_en' => 'accounts',            'desc_ar' => 'عرض قائمة الحسابات',               'desc_en' => 'Accounts Menu'],
            ['name' => 'invoices_menu',         'section_name_ar' => 'الحسابات',        'section_name_en' => 'accounts',            'desc_ar' => 'عرض قائمة الفواتير','desc_en' => 'Invoices Menu'],
            ['name' => 'journal_vouchers_menu', 'section_name_ar' => 'الحسابات',        'section_name_en' => 'accounts',            'desc_ar' => 'عرض قائمة القيود اليومية',        'desc_en' => 'Journal Vouchers Menu'],
            ['name' => 'bank_payments_menu',    'section_name_ar' => 'الحسابات',        'section_name_en' => 'accounts',            'desc_ar' => 'عرض قائمة سندات الصرف البنكية',   'desc_en' => 'Bank Payments Menu'],
            ['name' => 'bank_receipts_menu',    'section_name_ar' => 'الحسابات',        'section_name_en' => 'accounts',            'desc_ar' => 'عرض قائمة سندات القبض البنكية',   'desc_en' => 'Bank Receipts Menu'],


            //HR
            ['name' => 'employees_menu',        'section_name_ar' => 'شؤون الموظفين',   'section_name_en' => 'HR',                  'desc_ar' => 'عرض قائمة الموظفين', 'desc_en' => 'Employees Menu'],
            
            // Assets
            ['name' => 'cars_menu',             'section_name_ar' => 'الاصول',            'section_name_en' => 'assets',              'desc_ar' => 'عرض قائمة السيارات', 'desc_en' => 'Cars Menu'],

            // Admin
            ['name' => 'roles_menu',            'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'عرض قائمة الادوار','desc_en' => 'Roles Menu'],
            ['name' => 'departments_menu',      'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'عرض قائمة الاقسام', 'desc_en' => 'Departments Menu'],
            ['name' => 'companies_menu',        'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'عرض قائمة الشركات','desc_en' => 'Companies Menu'],
            ['name' => 'services_menu',         'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'عرض قائمة الخدمات','desc_en' => 'Services Menu'],
            ['name' => 'titles_menu',           'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'عرض قائمة الوظائف','desc_en' => 'Titles Menu'],
            ['name' => 'users_menu',            'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'عرض قائمة المستخدمين','desc_en' => 'Users Menu'],
            ['name' => 'statuses_menu',         'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'عرض قائمة الحالات','desc_en' => 'Statuses Menu'],
            ['name' => 'areas_menu',            'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'عرض قائمة المناطق','desc_en' => 'Areas Menu'],
            ['name' => 'settings_menu',         'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'عرض قائمة الاعدادات','desc_en' => 'Settings Menu'],
            ['name' => 'shifts_menu',           'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'عرض قائمة الشيفتات','desc_en' => 'Shifts Menu'],
           
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
