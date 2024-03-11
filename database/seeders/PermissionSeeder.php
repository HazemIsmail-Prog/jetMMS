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
            ['name' => 'dashboard_menu',            'section_name_ar' => 'لوحة التحكم',     'section_name_en' => 'dashboard',           'desc_ar' => 'عرض قائمة لوحة المعلومات',                'desc_en' => 'Dashboard Menu'],

            // Operations
            ['name' => 'operations_menu',           'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',          'desc_ar' => 'عرض قسم العمليات',         'desc_en' => 'Create Customers'],

            // Customers
            ['name' => 'customers_menu',            'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',          'desc_ar' => 'عرض قائمة العملاء',         'desc_en' => 'Create Customers'],
            ['name' => 'customers_create',          'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',          'desc_ar' => 'اضافة عملاء',               'desc_en' => 'Create Customers'],
            ['name' => 'customers_edit',            'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',          'desc_ar' => 'تعديل عملاء',               'desc_en' => 'Edit Customers'],
            ['name' => 'customers_delete',          'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',          'desc_ar' => 'حذف عملاء',               'desc_en' => 'Delete Customers'],
            
            // Orders
            ['name' => 'orders_menu',               'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',          'desc_ar' => 'عرض قائمة الطلبات',               'desc_en' => 'Orders Menu'],
            ['name' => 'orders_create',             'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',          'desc_ar' => 'انشاء الطلبات',               'desc_en' => 'Create Orders'],
            ['name' => 'orders_edit',               'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',          'desc_ar' => 'تعديل الطلبات',               'desc_en' => 'Edit Orders'],
            
            // Marketing
            ['name' => 'marketing_menu',            'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',          'desc_ar' => 'عرض قائمة التسويق',               'desc_en' => 'Marketing Menu'],
            ['name' => 'marketing_create',          'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',          'desc_ar' => 'اضافة تسويق',               'desc_en' => 'Marketing Menu'],
            ['name' => 'marketing_edit',            'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',          'desc_ar' => 'تعديل تسويق',               'desc_en' => 'Marketing Menu'],
            ['name' => 'marketing_delete',          'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',          'desc_ar' => 'حذف تسويق',               'desc_en' => 'Marketing Menu'],
            
            // Dispatching
            ['name' => 'dispatching_menu',          'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',          'desc_ar' => 'عرض قائمة التوزيع',               'desc_en' => 'Dispatching Menu'],

            // Cashier
            ['name' => 'cashier_menu',              'section_name_ar' => 'التحصيل',         'section_name_en' => 'cashier',             'desc_ar' => 'عرض قسم التحصيل',               'desc_en' => 'Access Cashier Section'],
            ['name' => 'cash_collection_menu',      'section_name_ar' => 'التحصيل',         'section_name_en' => 'cashier',             'desc_ar' => 'عرض قائمة التحصيل النقدي',               'desc_en' => 'Access Cashier Section'],
            ['name' => 'knet_collection_menu',      'section_name_ar' => 'التحصيل',         'section_name_en' => 'cashier',             'desc_ar' => 'عرض قائمة تحصيل الكي نت',               'desc_en' => 'Access Cashier Section'],
            ['name' => 'part_invoices_menu',        'section_name_ar' => 'التحصيل',         'section_name_en' => 'cashier',             'desc_ar' => 'عرض قائمة فواتير بضاعة العهدة',               'desc_en' => 'Access Cashier Section'],
            ['name' => 'part_invoices_create',      'section_name_ar' => 'التحصيل',         'section_name_en' => 'cashier',             'desc_ar' => 'اضافة فاتورة بضاعة عهدة',               'desc_en' => 'Access Cashier Section'],
            ['name' => 'part_invoices_edit',        'section_name_ar' => 'التحصيل',         'section_name_en' => 'cashier',             'desc_ar' => 'تعديل فاتورة بضاعة عهدة',               'desc_en' => 'Access Cashier Section'],
            ['name' => 'part_invoices_delete',      'section_name_ar' => 'التحصيل',         'section_name_en' => 'cashier',             'desc_ar' => 'حذف فاتورة بضاعة عهدة',               'desc_en' => 'Access Cashier Section'],

            // Accounting
            ['name' => 'accounting_menu',           'section_name_ar' => 'الحسابات',        'section_name_en' => 'accounts',            'desc_ar' => 'عرض قسم الحسابات',               'desc_en' => 'Accounts Menu'],
            ['name' => 'accounts_menu',             'section_name_ar' => 'الحسابات',        'section_name_en' => 'accounts',            'desc_ar' => 'عرض قائمة الحسابات',               'desc_en' => 'Accounts Menu'],
            ['name' => 'accounts_create',           'section_name_ar' => 'الحسابات',        'section_name_en' => 'accounts',            'desc_ar' => 'اضافة حساب',               'desc_en' => 'Accounts Menu'],
            ['name' => 'accounts_edit',             'section_name_ar' => 'الحسابات',        'section_name_en' => 'accounts',            'desc_ar' => 'تعديل حساب',               'desc_en' => 'Accounts Menu'],
            ['name' => 'accounts_delete',           'section_name_ar' => 'الحسابات',        'section_name_en' => 'accounts',            'desc_ar' => 'حذف حساب',               'desc_en' => 'Accounts Menu'],
            ['name' => 'invoices_menu',             'section_name_ar' => 'الحسابات',        'section_name_en' => 'accounts',            'desc_ar' => 'عرض قائمة الفواتير','desc_en' => 'Invoices Menu'],
            ['name' => 'journal_vouchers_menu',     'section_name_ar' => 'الحسابات',        'section_name_en' => 'accounts',            'desc_ar' => 'عرض قائمة القيود اليومية',        'desc_en' => 'Journal Vouchers Menu'],
            ['name' => 'journal_vouchers_create',   'section_name_ar' => 'الحسابات',        'section_name_en' => 'accounts',            'desc_ar' => 'اضافة قيود يومية',        'desc_en' => 'Journal Vouchers Menu'],
            ['name' => 'journal_vouchers_edit',     'section_name_ar' => 'الحسابات',        'section_name_en' => 'accounts',            'desc_ar' => 'تعديل قيود يومية',        'desc_en' => 'Journal Vouchers Menu'],
            ['name' => 'journal_vouchers_view',     'section_name_ar' => 'الحسابات',        'section_name_en' => 'accounts',            'desc_ar' => 'عرض قيود يومية',        'desc_en' => 'Journal Vouchers Menu'],
            ['name' => 'journal_vouchers_delete',   'section_name_ar' => 'الحسابات',        'section_name_en' => 'accounts',            'desc_ar' => 'حذف قيود يومية',        'desc_en' => 'Journal Vouchers Menu'],
            ['name' => 'accounting_report_menu',    'section_name_ar' => 'الحسابات',        'section_name_en' => 'accounts',            'desc_ar' => 'عرض قائمة تقارير الحسابات',               'desc_en' => 'Accounts Menu'],
            ['name' => 'account_statement_report',  'section_name_ar' => 'الحسابات',        'section_name_en' => 'accounts',            'desc_ar' => 'عرض تقرير كشف الحساب',               'desc_en' => 'Accounts Menu'],


            //HR
            ['name' => 'hr_menu',                   'section_name_ar' => 'شؤون الموظفين',   'section_name_en' => 'HR',                  'desc_ar' => 'عرض قسم شؤون الموظفين', 'desc_en' => 'Employees Menu'],
            ['name' => 'employees_menu',            'section_name_ar' => 'شؤون الموظفين',   'section_name_en' => 'HR',                  'desc_ar' => 'عرض قائمة الموظفين', 'desc_en' => 'Employees Menu'],
            ['name' => 'employees_create',          'section_name_ar' => 'شؤون الموظفين',   'section_name_en' => 'HR',                  'desc_ar' => 'اضافة موظف', 'desc_en' => 'Employees Menu'],
            ['name' => 'employees_edit',            'section_name_ar' => 'شؤون الموظفين',   'section_name_en' => 'HR',                  'desc_ar' => 'تعديل موظف', 'desc_en' => 'Employees Menu'],
            ['name' => 'employees_view',            'section_name_ar' => 'شؤون الموظفين',   'section_name_en' => 'HR',                  'desc_ar' => 'عرض موظف', 'desc_en' => 'Employees Menu'],
            ['name' => 'employees_delete',          'section_name_ar' => 'شؤون الموظفين',   'section_name_en' => 'HR',                  'desc_ar' => 'حذف موظف', 'desc_en' => 'Employees Menu'],
            ['name' => 'employees_attachment',      'section_name_ar' => 'شؤون الموظفين',   'section_name_en' => 'HR',                  'desc_ar' => 'مرفقات موظف', 'desc_en' => 'Employees Menu'],
            ['name' => 'hr_report_menu',            'section_name_ar' => 'شؤون الموظفين',   'section_name_en' => 'HR',                  'desc_ar' => 'عرض تقارير قسم شؤون الموظفين', 'desc_en' => 'Employees Menu'],
            
            // Assets
            ['name' => 'assets_menu',               'section_name_ar' => 'الاصول',            'section_name_en' => 'assets',              'desc_ar' => 'عرض قسم الاصول', 'desc_en' => 'Cars Menu'],
            ['name' => 'cars_menu',                 'section_name_ar' => 'الاصول',            'section_name_en' => 'assets',              'desc_ar' => 'عرض قائمة السيارات', 'desc_en' => 'Cars Menu'],
            ['name' => 'cars_create',               'section_name_ar' => 'الاصول',            'section_name_en' => 'assets',              'desc_ar' => 'اضافة سيارة', 'desc_en' => 'Cars Menu'],
            ['name' => 'cars_edit',                 'section_name_ar' => 'الاصول',            'section_name_en' => 'assets',              'desc_ar' => 'تعديل سيارة', 'desc_en' => 'Cars Menu'],
            ['name' => 'cars_delete',               'section_name_ar' => 'الاصول',            'section_name_en' => 'assets',              'desc_ar' => 'حذف سيارة', 'desc_en' => 'Cars Menu'],
            ['name' => 'cars_attachment',           'section_name_ar' => 'الاصول',            'section_name_en' => 'assets',              'desc_ar' => 'مرفقات سيارة', 'desc_en' => 'Cars Menu'],
            ['name' => 'assets_report_menu',        'section_name_ar' => 'الاصول',            'section_name_en' => 'assets',              'desc_ar' => 'عرض تقارير قسم الاصول', 'desc_en' => 'Cars Menu'],

            // Admin
            ['name' => 'admin_menu',                'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'عرض قسم مسؤول النظام','desc_en' => 'Roles Menu'],
            ['name' => 'suppliers_menu',            'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'عرض قائمة الموردين','desc_en' => 'Roles Menu'],
            ['name' => 'suppliers_create',          'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'اضافة مورد','desc_en' => 'Roles Menu'],
            ['name' => 'suppliers_edit',            'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'تعديل مورد','desc_en' => 'Roles Menu'],
            ['name' => 'suppliers_delete',          'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'حذف مورد','desc_en' => 'Roles Menu'],
            ['name' => 'const_centers_menu',        'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'عرض قائمة مراكز التكلفة','desc_en' => 'Roles Menu'],
            ['name' => 'const_centers_create',      'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'اضافة مركز تكلفة','desc_en' => 'Roles Menu'],
            ['name' => 'const_centers_edit',        'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'تعديل مركز تكلفة','desc_en' => 'Roles Menu'],
            ['name' => 'const_centers_delete',      'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'حذف مركز تكلفة','desc_en' => 'Roles Menu'],
            ['name' => 'roles_menu',                'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'عرض قائمة الادوار','desc_en' => 'Roles Menu'],
            ['name' => 'roles_create',              'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'اضافة دور','desc_en' => 'Roles Menu'],
            ['name' => 'roles_edit',                'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'تعديل دور','desc_en' => 'Roles Menu'],
            ['name' => 'roles_delete',              'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'حذف دور','desc_en' => 'Roles Menu'],
            ['name' => 'users_menu',                'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'عرض قائمة المستخدمين','desc_en' => 'Users Menu'],
            ['name' => 'users_create',              'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'اضافة مستخدم','desc_en' => 'Users Menu'],
            ['name' => 'users_edit',                'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'تعديل مستخدم','desc_en' => 'Users Menu'],
            ['name' => 'users_delete',              'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'حذف مستخدم','desc_en' => 'Users Menu'],
            ['name' => 'titles_menu',               'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'عرض قائمة الوظائف','desc_en' => 'Statuses Menu'],
            ['name' => 'titles_create',             'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'اضافة وظيفة','desc_en' => 'Statuses Menu'],
            ['name' => 'titles_edit',               'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'تعديل وظيفة','desc_en' => 'Statuses Menu'],
            ['name' => 'titles_delete',             'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'حذف وظيفة','desc_en' => 'Statuses Menu'],
            ['name' => 'statuses_menu',             'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'عرض قائمة الحالات','desc_en' => 'Statuses Menu'],
            ['name' => 'statuses_edit',             'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'تعديل الحالات','desc_en' => 'Statuses Menu'],
            ['name' => 'departments_menu',          'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'عرض قائمة الاقسام','desc_en' => 'Statuses Menu'],
            ['name' => 'departments_create',        'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'اضافة قسم','desc_en' => 'Statuses Menu'],
            ['name' => 'departments_edit',          'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'تعديل قسم','desc_en' => 'Statuses Menu'],
            ['name' => 'departments_delete',        'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'حذف قسم','desc_en' => 'Statuses Menu'],
            ['name' => 'companies_menu',            'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'عرض قائمة الشركات','desc_en' => 'Statuses Menu'],
            ['name' => 'companies_create',          'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'اضافة شركة','desc_en' => 'Statuses Menu'],
            ['name' => 'companies_edit',            'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'تعديل شركة','desc_en' => 'Statuses Menu'],
            ['name' => 'companies_delete',          'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'حذف شركة','desc_en' => 'Statuses Menu'],
            ['name' => 'shifts_menu',               'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'عرض قائمة الشيفتات','desc_en' => 'Shifts Menu'],
            ['name' => 'shifts_create',             'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'اضافة شيفت','desc_en' => 'Shifts Menu'],
            ['name' => 'shifts_edit',               'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'تعديل شيفت','desc_en' => 'Shifts Menu'],
            ['name' => 'shifts_delete',             'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'حذف شيفت','desc_en' => 'Shifts Menu'],
            ['name' => 'areas_menu',                'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'عرض قائمة المناطق','desc_en' => 'Shifts Menu'],
            ['name' => 'areas_create',              'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'اضافة منطقة','desc_en' => 'Shifts Menu'],
            ['name' => 'areas_edit',                'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'تعديل منطقة','desc_en' => 'Shifts Menu'],
            ['name' => 'areas_delete',              'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'حذف منطقة','desc_en' => 'Shifts Menu'],
            ['name' => 'services_menu',             'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'عرض قائمة الخدمات','desc_en' => 'Shifts Menu'],
            ['name' => 'services_create',           'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'اضافة خدمة','desc_en' => 'Shifts Menu'],
            ['name' => 'services_edit',             'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'تعديل خدمة','desc_en' => 'Shifts Menu'],
            ['name' => 'services_delete',           'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'حذف خدمة','desc_en' => 'Shifts Menu'],
            ['name' => 'settings_menu',             'section_name_ar' => 'مسؤول النظام',    'section_name_en' => 'admin',               'desc_ar' => 'عرض قائمة اعدادت النظام','desc_en' => 'Shifts Menu'],
           
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
