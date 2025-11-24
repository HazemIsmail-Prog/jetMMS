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
            ['name' => 'dashboard_menu',                'section_name_ar' => 'لوحة التحكم',     'section_name_en' => 'dashboard',                   'desc_ar' => 'عرض قائمة لوحة المعلومات',                                                    'desc_en' => 'Dashboard Menu'],

            // Customers
            ['name' => 'customers_menu',                'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',                  'desc_ar' => 'عرض قائمة العملاء',                                                   'desc_en' => 'Create Customers'],
            ['name' => 'customers_create',              'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',                  'desc_ar' => 'اضافة عملاء',                                                 'desc_en' => 'Create Customers'],
            ['name' => 'customers_edit',                'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',                  'desc_ar' => 'تعديل عملاء',                                                 'desc_en' => 'Edit Customers'],
            ['name' => 'customers_delete',              'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',                  'desc_ar' => 'حذف عملاء',                                                   'desc_en' => 'Delete Customers'],
            
            // Orders
            ['name' => 'orders_menu',                   'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',                  'desc_ar' => 'عرض قائمة الطلبات',                                                   'desc_en' => 'Orders Menu'],
            ['name' => 'orders_create',                 'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',                  'desc_ar' => 'انشاء الطلبات',                                                   'desc_en' => 'Create Orders'],
            ['name' => 'orders_edit',                   'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',                  'desc_ar' => 'تعديل الطلبات',                                                   'desc_en' => 'Edit Orders'],
            ['name' => 'orders_cancel',                 'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',                  'desc_ar' => 'الغاء الطلبات',                                                   'desc_en' => 'Edit Orders'],
            ['name' => 'orders_hold',                   'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',                  'desc_ar' => 'توقف مؤقت للطلبات',                                                   'desc_en' => 'Edit Orders'],
            ['name' => 'orders_invoices',               'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',                  'desc_ar' => 'عرض فواتير الطلب',                                                    'desc_en' => 'Edit Orders'],
            ['name' => 'orders_comments',               'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',                  'desc_ar' => 'عرض تعليقات الطلب',                                                   'desc_en' => 'Edit Orders'],
            ['name' => 'orders_progress',               'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',                  'desc_ar' => 'عرض خطوات الطلب',                                                 'desc_en' => 'Edit Orders'],
            ['name' => 'orders_send_survey',            'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',                  'desc_ar' => 'ارسال استبيان',                                                   'desc_en' => 'Send Survey'],
            
            // Marketing
            ['name' => 'marketing_menu',                'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',                  'desc_ar' => 'عرض قائمة التسويق',                                                   'desc_en' => 'Marketing Menu'],
            ['name' => 'marketing_create',              'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',                  'desc_ar' => 'اضافة تسويق',                                                 'desc_en' => 'Marketing Menu'],
            ['name' => 'marketing_edit',                'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',                  'desc_ar' => 'تعديل تسويق',                                                 'desc_en' => 'Marketing Menu'],
            ['name' => 'marketing_delete',              'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',                  'desc_ar' => 'حذف تسويق',                                                   'desc_en' => 'Marketing Menu'],
            
            // Rating
            ['name' => 'rating_menu',                   'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',                  'desc_ar' => 'عرض قائمة التقييمات',                                                 'desc_en' => 'Rating Menu'],

            // Invoices
            ['name' => 'invoices_menu',                 'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',                  'desc_ar' => 'عرض قائمة الفواتير',                                                  'desc_en' => 'Invoices Menu'],
            ['name' => 'invoices_create',               'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',                  'desc_ar' => 'انشاء فاتورة',                                                    'desc_en' => 'Invoices Menu'],
            ['name' => 'invoices_delete',               'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',                  'desc_ar' => 'حذف فاتورة',                                                  'desc_en' => 'Invoices Menu'],
            ['name' => 'invoices_discount',             'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',                  'desc_ar' => 'تطبيق خصم',                                                   'desc_en' => 'Applu Discount'],
            
            // Payments
            ['name' => 'payments_create',               'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',                  'desc_ar' => 'انشاء دفعة',                                                  'desc_en' => 'Invoices Menu'],
            ['name' => 'payments_delete',               'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',                  'desc_ar' => 'حذف دفعة',                                                    'desc_en' => 'Invoices Menu'],

            // Dispatching
            ['name' => 'dispatching_menu',              'section_name_ar' => 'العمليات',        'section_name_en' => 'operations',                  'desc_ar' => 'عرض قائمة التوزيع',                                                   'desc_en' => 'Dispatching Menu'],

            // Cashier
            ['name' => 'cash_collection_menu',          'section_name_ar' => 'التحصيل',         'section_name_en' => 'cashier',                     'desc_ar' => 'عرض قائمة التحصيل النقدي',                                                    'desc_en' => 'Access Cashier Section'],
            
            ['name' => 'knet_collection_menu',          'section_name_ar' => 'التحصيل',         'section_name_en' => 'cashier',                     'desc_ar' => 'عرض قائمة تحصيل الكي نت',                                                 'desc_en' => 'Access Cashier Section'],
            
            ['name' => 'part_invoices_menu',            'section_name_ar' => 'التحصيل',         'section_name_en' => 'cashier',                     'desc_ar' => 'عرض قائمة فواتير بضاعة العهدة',                                                   'desc_en' => 'Access Cashier Section'],
            ['name' => 'part_invoices_create',          'section_name_ar' => 'التحصيل',         'section_name_en' => 'cashier',                     'desc_ar' => 'اضافة فاتورة بضاعة عهدة',                                                 'desc_en' => 'Access Cashier Section'],
            ['name' => 'part_invoices_edit',            'section_name_ar' => 'التحصيل',         'section_name_en' => 'cashier',                     'desc_ar' => 'تعديل فاتورة بضاعة عهدة',                                                 'desc_en' => 'Access Cashier Section'],
            ['name' => 'part_invoices_delete',          'section_name_ar' => 'التحصيل',         'section_name_en' => 'cashier',                     'desc_ar' => 'حذف فاتورة بضاعة عهدة',                                                   'desc_en' => 'Access Cashier Section'],

            // Accounting
            ['name' => 'accounts_menu',                 'section_name_ar' => 'الحسابات',        'section_name_en' => 'accounts',                    'desc_ar' => 'عرض قائمة الحسابات',                                                  'desc_en' => 'Accounts Menu'],
            ['name' => 'accounts_create',               'section_name_ar' => 'الحسابات',        'section_name_en' => 'accounts',                    'desc_ar' => 'اضافة حساب',                                                  'desc_en' => 'Accounts Menu'],
            ['name' => 'accounts_edit',                 'section_name_ar' => 'الحسابات',        'section_name_en' => 'accounts',                    'desc_ar' => 'تعديل حساب',                                                  'desc_en' => 'Accounts Menu'],
            ['name' => 'accounts_delete',               'section_name_ar' => 'الحسابات',        'section_name_en' => 'accounts',                    'desc_ar' => 'حذف حساب',                                                    'desc_en' => 'Accounts Menu'],
            
           
            ['name' => 'journal_vouchers_menu',         'section_name_ar' => 'الحسابات',        'section_name_en' => 'accounts',                    'desc_ar' => 'عرض قائمة القيود اليومية',                                                    'desc_en' => 'Journal Vouchers Menu'],
            ['name' => 'journal_vouchers_create',       'section_name_ar' => 'الحسابات',        'section_name_en' => 'accounts',                    'desc_ar' => 'اضافة قيود يومية',                                                    'desc_en' => 'Journal Vouchers Menu'],
            ['name' => 'journal_vouchers_edit',         'section_name_ar' => 'الحسابات',        'section_name_en' => 'accounts',                    'desc_ar' => 'تعديل قيود يومية',                                                    'desc_en' => 'Journal Vouchers Menu'],
            ['name' => 'journal_vouchers_view',         'section_name_ar' => 'الحسابات',        'section_name_en' => 'accounts',                    'desc_ar' => 'عرض قيود يومية',                                                  'desc_en' => 'Journal Vouchers Menu'],
            ['name' => 'journal_vouchers_delete',       'section_name_ar' => 'الحسابات',        'section_name_en' => 'accounts',                    'desc_ar' => 'حذف قيود يومية',                                                  'desc_en' => 'Journal Vouchers Menu'],
            
            ['name' => 'account_statement_report',      'section_name_ar' => 'الحسابات',        'section_name_en' => 'accounts',                    'desc_ar' => 'عرض تقرير كشف الحساب',                                                    'desc_en' => 'Accounts Menu'],
            ['name' => 'balance_sheet_report',          'section_name_ar' => 'الحسابات',        'section_name_en' => 'accounts',                    'desc_ar' => 'عرض الميزانية العمومية',                                                  'desc_en' => 'Accounts Menu'],
            ['name' => 'trial_balance_report',          'section_name_ar' => 'الحسابات',        'section_name_en' => 'accounts',                    'desc_ar' => 'عرض ميزان المراجعة',                                                  'desc_en' => 'Accounts Menu'],
            ['name' => 'profit_loss_report',            'section_name_ar' => 'الحسابات',        'section_name_en' => 'accounts',                    'desc_ar' => 'عرض تقرير الارباح والخسائر',                                                  'desc_en' => 'Accounts Menu'],


            //HR
            ['name' => 'employees_menu',                'section_name_ar' => 'شؤون الموظفين',       'section_name_en' => 'HR',                      'desc_ar' => 'عرض قائمة الموظفين',                                                  'desc_en' => 'Employees Menu'],
            ['name' => 'employees_create',              'section_name_ar' => 'شؤون الموظفين',       'section_name_en' => 'HR',                      'desc_ar' => 'اضافة موظف',                                                  'desc_en' => 'Employees Menu'],
            ['name' => 'employees_edit',                'section_name_ar' => 'شؤون الموظفين',       'section_name_en' => 'HR',                      'desc_ar' => 'تعديل موظف',                                                  'desc_en' => 'Employees Menu'],
            ['name' => 'employees_view',                'section_name_ar' => 'شؤون الموظفين',       'section_name_en' => 'HR',                      'desc_ar' => 'عرض موظف',                                                    'desc_en' => 'Employees Menu'],
            ['name' => 'employees_delete',              'section_name_ar' => 'شؤون الموظفين',       'section_name_en' => 'HR',                      'desc_ar' => 'حذف موظف',                                                    'desc_en' => 'Employees Menu'],
            ['name' => 'employees_attachment',          'section_name_ar' => 'شؤون الموظفين',       'section_name_en' => 'HR',                      'desc_ar' => 'مرفقات موظف',                                                 'desc_en' => 'Employees Menu'],
            ['name' => 'leaves_menu',                   'section_name_ar' => 'شؤون الموظفين',       'section_name_en' => 'HR',                      'desc_ar' => 'عرض قائمة الاجازات',                                                  'desc_en' => 'Employees Menu'],
            ['name' => 'leaves_create',                 'section_name_ar' => 'شؤون الموظفين',       'section_name_en' => 'HR',                      'desc_ar' => 'اضافة اجازة',                                                 'desc_en' => 'Employees Menu'],
            ['name' => 'leaves_edit',                   'section_name_ar' => 'شؤون الموظفين',       'section_name_en' => 'HR',                      'desc_ar' => 'تعديل اجازة',                                                 'desc_en' => 'Employees Menu'],
            ['name' => 'leaves_delete',                 'section_name_ar' => 'شؤون الموظفين',       'section_name_en' => 'HR',                      'desc_ar' => 'حذف اجازة',                                                   'desc_en' => 'Employees Menu'],
            ['name' => 'leaves_attachment',             'section_name_ar' => 'شؤون الموظفين',       'section_name_en' => 'HR',                      'desc_ar' => 'مرفقات الاجازات',                                                 'desc_en' => 'Employees Menu'],
            ['name' => 'increases_menu',                'section_name_ar' => 'شؤون الموظفين',       'section_name_en' => 'HR',                      'desc_ar' => 'عرض قائمة الزيادات',                                                  'desc_en' => 'Employees Menu'],
            ['name' => 'increases_create',              'section_name_ar' => 'شؤون الموظفين',       'section_name_en' => 'HR',                      'desc_ar' => 'اضافة زيادة',                                                 'desc_en' => 'Employees Menu'],
            ['name' => 'increases_edit',                'section_name_ar' => 'شؤون الموظفين',       'section_name_en' => 'HR',                      'desc_ar' => 'تعديل زيادة',                                                 'desc_en' => 'Employees Menu'],
            ['name' => 'increases_delete',              'section_name_ar' => 'شؤون الموظفين',       'section_name_en' => 'HR',                      'desc_ar' => 'حذف زيادة',                                                   'desc_en' => 'Employees Menu'],
            ['name' => 'increases_attachment',          'section_name_ar' => 'شؤون الموظفين',       'section_name_en' => 'HR',                      'desc_ar' => 'مرفقات الزيادات',                                                 'desc_en' => 'Employees Menu'],
            ['name' => 'absences_menu',                 'section_name_ar' => 'شؤون الموظفين',       'section_name_en' => 'HR',                      'desc_ar' => 'عرض قائمة الغيابات',                                                  'desc_en' => 'Employees Menu'],
            ['name' => 'absences_create',               'section_name_ar' => 'شؤون الموظفين',       'section_name_en' => 'HR',                      'desc_ar' => 'اضافة غياب',                                                  'desc_en' => 'Employees Menu'],
            ['name' => 'absences_edit',                 'section_name_ar' => 'شؤون الموظفين',       'section_name_en' => 'HR',                      'desc_ar' => 'تعديل غياب',                                                  'desc_en' => 'Employees Menu'],
            ['name' => 'absences_delete',               'section_name_ar' => 'شؤون الموظفين',       'section_name_en' => 'HR',                      'desc_ar' => 'حذف غياب',                                                    'desc_en' => 'Employees Menu'],
            ['name' => 'absences_attachment',           'section_name_ar' => 'شؤون الموظفين',       'section_name_en' => 'HR',                      'desc_ar' => 'مرفقات الغيابات',                                                 'desc_en' => 'Employees Menu'],
            ['name' => 'salary_actions_menu',           'section_name_ar' => 'شؤون الموظفين',       'section_name_en' => 'HR',                      'desc_ar' => 'عرض قائمة تعديلات الراتب',                                                    'desc_en' => 'Employees Menu'],
            ['name' => 'salary_actions_create',         'section_name_ar' => 'شؤون الموظفين',       'section_name_en' => 'HR',                      'desc_ar' => 'اضافة تعديل الراتب',                                                  'desc_en' => 'Employees Menu'],
            ['name' => 'salary_actions_edit',           'section_name_ar' => 'شؤون الموظفين',       'section_name_en' => 'HR',                      'desc_ar' => 'تعديل تعديل الراتب',                                                  'desc_en' => 'Employees Menu'],
            ['name' => 'salary_actions_delete',         'section_name_ar' => 'شؤون الموظفين',       'section_name_en' => 'HR',                      'desc_ar' => 'حذف تعديل الراتب',                                                    'desc_en' => 'Employees Menu'],
            ['name' => 'salary_actions_attachment',     'section_name_ar' => 'شؤون الموظفين',       'section_name_en' => 'HR',                      'desc_ar' => 'مرفقات تعديلات الراتب',                                                   'desc_en' => 'Employees Menu'],
            
            // Assets
            ['name' => 'cars_menu',                     'section_name_ar' => 'الاصول',                'section_name_en' => 'assets',                 'desc_ar' => 'عرض قائمة السيارات',                                                 'desc_en' => 'Cars Menu'],
            ['name' => 'cars_create',                   'section_name_ar' => 'الاصول',                'section_name_en' => 'assets',                 'desc_ar' => 'اضافة سيارة',                                                    'desc_en' => 'Cars Menu'],
            ['name' => 'cars_edit',                     'section_name_ar' => 'الاصول',                'section_name_en' => 'assets',                 'desc_ar' => 'تعديل سيارة',                                                    'desc_en' => 'Cars Menu'],
            ['name' => 'cars_delete',                   'section_name_ar' => 'الاصول',                'section_name_en' => 'assets',                 'desc_ar' => 'حذف سيارة',                                                  'desc_en' => 'Cars Menu'],
            ['name' => 'cars_attachment',               'section_name_ar' => 'الاصول',                'section_name_en' => 'assets',                 'desc_ar' => 'مرفقات سيارة',                                                   'desc_en' => 'Cars Menu'],
            ['name' => 'car_actions_menu',              'section_name_ar' => 'الاصول',                'section_name_en' => 'assets',                 'desc_ar' => 'عرض حركات السيارة',                                                  'desc_en' => 'Cars Menu'],
            ['name' => 'car_actions_create',            'section_name_ar' => 'الاصول',                'section_name_en' => 'assets',                 'desc_ar' => 'اضافة حركة سيارة',                                                   'desc_en' => 'Cars Menu'],
            ['name' => 'car_actions_edit',              'section_name_ar' => 'الاصول',                'section_name_en' => 'assets',                 'desc_ar' => 'تعديل حركة سيارة',                                                   'desc_en' => 'Cars Menu'],
            ['name' => 'car_actions_delete',            'section_name_ar' => 'الاصول',                'section_name_en' => 'assets',                 'desc_ar' => 'حذف حركة سيارة',                                                 'desc_en' => 'Cars Menu'],
            ['name' => 'car_services_menu',             'section_name_ar' => 'الاصول',                'section_name_en' => 'assets',                 'desc_ar' => 'عرض صيانات السيارة',                                                 'desc_en' => 'Cars Menu'],
            ['name' => 'car_services_create',           'section_name_ar' => 'الاصول',                'section_name_en' => 'assets',                 'desc_ar' => 'اضافة صيانة سيارة',                                                  'desc_en' => 'Cars Menu'],
            ['name' => 'car_services_edit',             'section_name_ar' => 'الاصول',                'section_name_en' => 'assets',                 'desc_ar' => 'تعديل صيانة سيارة',                                                  'desc_en' => 'Cars Menu'],
            ['name' => 'car_services_delete',           'section_name_ar' => 'الاصول',                'section_name_en' => 'assets',                 'desc_ar' => 'حذف صيانة سيارة',                                                    'desc_en' => 'Cars Menu'],

            // Admin
            ['name' => 'suppliers_menu',                'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'عرض قائمة الموردين',                'desc_en' => 'Roles Menu'],
            ['name' => 'suppliers_create',              'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'اضافة مورد',                        'desc_en' => 'Roles Menu'],
            ['name' => 'suppliers_edit',                'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'تعديل مورد',                        'desc_en' => 'Roles Menu'],
            ['name' => 'suppliers_delete',              'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'حذف مورد',                          'desc_en' => 'Roles Menu'],
            ['name' => 'const_centers_menu',            'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'عرض قائمة مراكز التكلفة',           'desc_en' => 'Roles Menu'],
            ['name' => 'const_centers_create',          'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'اضافة مركز تكلفة',                  'desc_en' => 'Roles Menu'],
            ['name' => 'const_centers_edit',            'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'تعديل مركز تكلفة',                  'desc_en' => 'Roles Menu'],
            ['name' => 'const_centers_delete',          'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'حذف مركز تكلفة',                    'desc_en' => 'Roles Menu'],
            ['name' => 'roles_menu',                    'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'عرض قائمة الادوار',                 'desc_en' => 'Roles Menu'],
            ['name' => 'roles_create',                  'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'اضافة دور',                         'desc_en' => 'Roles Menu'],
            ['name' => 'roles_edit',                    'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'تعديل دور',                         'desc_en' => 'Roles Menu'],
            ['name' => 'roles_delete',                  'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'حذف دور',                           'desc_en' => 'Roles Menu'],
            ['name' => 'permission_menu',               'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'عرض قائمة الصلاحيات',               'desc_en' => 'Permission Menu'],
            ['name' => 'permission_create',             'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'اضافة صلاحية',                      'desc_en' => 'Permission Menu'],
            ['name' => 'permission_edit',               'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'تعديل صلاحية',                      'desc_en' => 'Permission Menu'],
            ['name' => 'permission_delete',             'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'حذف صلاحية',                        'desc_en' => 'Permission Menu'],
            ['name' => 'users_menu',                    'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'عرض قائمة المستخدمين',              'desc_en' => 'Users Menu'],
            ['name' => 'users_create',                  'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'اضافة مستخدم',                      'desc_en' => 'Users Menu'],
            ['name' => 'users_edit',                    'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'تعديل مستخدم',                      'desc_en' => 'Users Menu'],
            ['name' => 'users_delete',                  'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'حذف مستخدم',                        'desc_en' => 'Users Menu'],
            ['name' => 'titles_menu',                   'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'عرض قائمة الوظائف',                 'desc_en' => 'Statuses Menu'],
            ['name' => 'titles_create',                 'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'اضافة وظيفة',                       'desc_en' => 'Statuses Menu'],
            ['name' => 'titles_edit',                   'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'تعديل وظيفة',                       'desc_en' => 'Statuses Menu'],
            ['name' => 'titles_delete',                 'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'حذف وظيفة',                         'desc_en' => 'Statuses Menu'],
            ['name' => 'statuses_menu',                 'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'عرض قائمة الحالات',                 'desc_en' => 'Statuses Menu'],
            ['name' => 'statuses_edit',                 'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'تعديل الحالات',                     'desc_en' => 'Statuses Menu'],
            ['name' => 'departments_menu',              'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'عرض قائمة الاقسام',                 'desc_en' => 'Statuses Menu'],
            ['name' => 'departments_create',            'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'اضافة قسم',                         'desc_en' => 'Statuses Menu'],
            ['name' => 'departments_edit',              'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'تعديل قسم',                         'desc_en' => 'Statuses Menu'],
            ['name' => 'departments_delete',            'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'حذف قسم',                           'desc_en' => 'Statuses Menu'],
            ['name' => 'companies_menu',                'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'عرض قائمة الشركات',                 'desc_en' => 'Statuses Menu'],
            ['name' => 'companies_create',              'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'اضافة شركة',                        'desc_en' => 'Statuses Menu'],
            ['name' => 'companies_edit',                'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'تعديل شركة',                        'desc_en' => 'Statuses Menu'],
            ['name' => 'companies_delete',              'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'حذف شركة',                          'desc_en' => 'Statuses Menu'],
            ['name' => 'shifts_menu',                   'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'عرض قائمة الشيفتات',                'desc_en' => 'Shifts Menu'],
            ['name' => 'shifts_create',                 'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'اضافة شيفت',                        'desc_en' => 'Shifts Menu'],
            ['name' => 'shifts_edit',                   'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'تعديل شيفت',                        'desc_en' => 'Shifts Menu'],
            ['name' => 'shifts_delete',                 'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'حذف شيفت',                          'desc_en' => 'Shifts Menu'],
            ['name' => 'areas_menu',                    'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'عرض قائمة المناطق',                'desc_en' => 'Shifts Menu'],
            ['name' => 'areas_create',                  'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'اضافة منطقة',                      'desc_en' => 'Shifts Menu'],
            ['name' => 'areas_edit',                    'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'تعديل منطقة',                       'desc_en' => 'Shifts Menu'],
            ['name' => 'areas_delete',                  'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'حذف منطقة',                         'desc_en' => 'Shifts Menu'],
            ['name' => 'services_menu',                 'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'عرض قائمة الخدمات',                'desc_en' => 'Shifts Menu'],
            ['name' => 'services_create',               'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'اضافة خدمة',                        'desc_en' => 'Shifts Menu'],
            ['name' => 'services_edit',                 'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'تعديل خدمة',                        'desc_en' => 'Shifts Menu'],
            ['name' => 'services_delete',               'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'حذف خدمة',                          'desc_en' => 'Shifts Menu'],
            ['name' => 'settings_menu',                 'section_name_ar' => 'مسؤول النظام',        'section_name_en' => 'admin',                   'desc_ar' => 'عرض قائمة اعدادت النظام',          'desc_en' => 'Shifts Menu'],
           
            // Other income categories
            ['name' => 'other_income_categories_menu',          'section_name_ar' => 'انواع الايرادات الاخرى',        'section_name_en' => 'other_income_categories',                   'desc_ar' => 'عرض قائمة انواع الايرادات الاخرى',    'desc_en' => 'Other Income Categories Menu'],
            ['name' => 'other_income_categories_create',        'section_name_ar' => 'انواع الايرادات الاخرى',        'section_name_en' => 'other_income_categories',                   'desc_ar' => 'اضافة انواع الايرادات الاخرى',              'desc_en' => 'Other Income Categories Menu'],
            ['name' => 'other_income_categories_edit',          'section_name_ar' => 'انواع الايرادات الاخرى',        'section_name_en' => 'other_income_categories',                   'desc_ar' => 'تعديل انواع الايرادات الاخرى',              'desc_en' => 'Other Income Categories Menu'],
            ['name' => 'other_income_categories_delete',        'section_name_ar' => 'انواع الايرادات الاخرى',        'section_name_en' => 'other_income_categories',                   'desc_ar' => 'حذف انواع الايرادات الاخرى',                'desc_en' => 'Other Income Categories Menu'],
           
            // Income Invoices
            ['name' => 'income_invoices_menu',          'section_name_ar' => 'فواتير الايراد',        'section_name_en' => 'income_invoices',                   'desc_ar' => 'عرض قائمة فواتير الايراد',    'desc_en' => 'Income Invoices Menu'],
            ['name' => 'income_invoices_create',        'section_name_ar' => 'فواتير الايراد',        'section_name_en' => 'income_invoices',                   'desc_ar' => 'اضافة فاتورة ايراد',              'desc_en' => 'Income Invoices Menu'],
            ['name' => 'income_invoices_edit',          'section_name_ar' => 'فواتير الايراد',        'section_name_en' => 'income_invoices',                   'desc_ar' => 'تعديل فاتورة ايراد',              'desc_en' => 'Income Invoices Menu'],
            ['name' => 'income_invoices_delete',        'section_name_ar' => 'فواتير الايراد',        'section_name_en' => 'income_invoices',                   'desc_ar' => 'حذف فاتورة ايراد',                'desc_en' => 'Income Invoices Menu'],

            // Income Invoices Attachments
            ['name' => 'income_invoices_attachments_menu',          'section_name_ar' => 'فواتير الايراد',        'section_name_en' => 'income_invoices',                   'desc_ar' => 'عرض مرفقات فواتير الايراد',    'desc_en' => 'Income Invoices Attachments Menu'],
            ['name' => 'income_invoices_attachments_create',        'section_name_ar' => 'فواتير الايراد',        'section_name_en' => 'income_invoices',                   'desc_ar' => 'اضافة مرفق فواتير الايراد',              'desc_en' => 'Income Invoices Attachments Menu'],
            ['name' => 'income_invoices_attachments_edit',          'section_name_ar' => 'فواتير الايراد',        'section_name_en' => 'income_invoices',                   'desc_ar' => 'تعديل مرفق فواتير الايراد',              'desc_en' => 'Income Invoices Attachments Menu'],
            ['name' => 'income_invoices_attachments_delete',        'section_name_ar' => 'فواتير الايراد',        'section_name_en' => 'income_invoices',                   'desc_ar' => 'حذف مرفق فواتير الايراد',                'desc_en' => 'Income Invoices Attachments Menu'],

            // Income Invoices Payments
            ['name' => 'income_invoices_payments_menu',          'section_name_ar' => 'فواتير الايراد',        'section_name_en' => 'income_invoices',                   'desc_ar' => 'عرض دفعات فواتير الايراد',    'desc_en' => 'Income Invoices Payments Menu'],
            ['name' => 'income_invoices_payments_create',        'section_name_ar' => 'فواتير الايراد',        'section_name_en' => 'income_invoices',                   'desc_ar' => 'اضافة دفعة فواتير الايراد',              'desc_en' => 'Income Invoices Payments Menu'],
            ['name' => 'income_invoices_payments_edit',          'section_name_ar' => 'فواتير الايراد',        'section_name_en' => 'income_invoices',                   'desc_ar' => 'تعديل دفعة فواتير الايراد',              'desc_en' => 'Income Invoices Payments Menu'],
            ['name' => 'income_invoices_payments_delete',        'section_name_ar' => 'فواتير الايراد',        'section_name_en' => 'income_invoices',                   'desc_ar' => 'حذف دفعة فواتير الايراد',                'desc_en' => 'Income Invoices Payments Menu'],

            // Income Report
            ['name' => 'income_report',          'section_name_ar' => 'التقارير',        'section_name_en' => 'reports',                   'desc_ar' => 'عرض تقرير الايرادات',    'desc_en' => 'view income report'],
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
        // Role::find(1)->permissions()->attach(Permission::pluck('id'));
        
        //Run the following code to seed only permissions seeder
        //php artisan db:seed --class=PermissionSeeder

    }
}
