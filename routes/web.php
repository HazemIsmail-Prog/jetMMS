<?php

use App\Http\Controllers\LanguageController;
use App\Livewire\Accounts\AccountIndex;
use App\Livewire\Accounts\VoucherIndex;
use App\Livewire\Areas\AreaIndex;
use App\Livewire\Companies\CompanyIndex;
use App\Livewire\Customers\CustomerForm;
use App\Livewire\Customers\CustomerIndex;
use App\Livewire\Departments\DepartmentIndex;
use App\Livewire\Dispatching\DispatchingIndex;
use App\Livewire\Employees\EmployeeForm;
use App\Livewire\Employees\EmployeeIndex;
use App\Livewire\Employees\EmployeeView;
use App\Livewire\Fleet\ActionIndex;
use App\Livewire\Fleet\ActionReport;
use App\Livewire\Fleet\CarForm;
use App\Livewire\Fleet\CarIndex;
use App\Livewire\Marketing\MarketingIndex;
use App\Livewire\Orders\InvoiceIndex;
use App\Livewire\Orders\OrderIndex;
use App\Livewire\Roles\RoleIndex;
use App\Livewire\Services\ServiceIndex;
use App\Livewire\Settings\SettingsForm;
use App\Livewire\Shifts\ShiftIndex;
use App\Livewire\Statuses\StatusIndex;
use App\Livewire\TechnicianPage;
use App\Livewire\Titles\TitleIndex;
use App\Livewire\Users\UserForm;
use App\Livewire\Users\UserIndex;
use Illuminate\Support\Facades\Route;

Route::get('lang/{lang}', [LanguageController::class, 'switchLang'])->name('lang.swith');


Route::middleware([
    'auth:sanctum',
    'active',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/technician_page', TechnicianPage::class)->name('technician_page'); //livewire


    Route::group(['middleware' => 'no_technicians'], function () {

        // Dashboard
        Route::get('/', function () {
            return view('dashboard');
        })->name('dashboard');




        // ========== Operations ==========

        // Customers
        Route::get('customers', CustomerIndex::class)->name('customer.index');
        Route::get('customers/form/{customer?}', CustomerForm::class)->name('customer.form');

        // Orders
        Route::get('orders', OrderIndex::class)->name('order.index');

        // Marketings
        Route::get('marketings', MarketingIndex::class)->name('marketing.index');

        //Dispatching 
        Route::get('dispatch-panel/{department}', DispatchingIndex::class)->name('dispatch-panel.index');



        // ========== Accounting ==========

        // Accounts
        Route::get('accounts', AccountIndex::class)->name('account.index');

        // Invoices
        Route::get('invoices', InvoiceIndex::class)->name('invoice.index');

        // Vouchers
        Route::get('vouchers', VoucherIndex::class)->name('voucher.index');




        // ========== HR ==========

        // Employees
        Route::get('employees', EmployeeIndex::class)->name('employee.index');
        Route::get('employees/form/{employee?}', EmployeeForm::class)->name('employee.form');
        Route::get('employee/view/{employee}', EmployeeView::class)->name('employee.view');




        // ========== Assets ==========

        // Cars
        Route::get('cars', CarIndex::class)->name('car.index');
        Route::get('cars/form/{car?}', CarForm::class)->name('car.form');
        Route::get('car-actions/{car}', ActionIndex::class)->name('car.action.index');
        Route::get('car-action-report/{action}', ActionReport::class)->name('car.action.report');




        // ========== Admin Settings ==========

        // Roles
        Route::get('roles', RoleIndex::class)->name('role.index');


        // Users
        Route::get('users', UserIndex::class)->name('user.index');
        Route::get('users/form/{user?}', UserForm::class)->name('user.form');

        // Titles
        Route::get('titles', TitleIndex::class)->name('title.index');

        // Statuses
        Route::get('statuses', StatusIndex::class)->name('status.index');

        // Departments
        Route::get('departments', DepartmentIndex::class)->name('department.index');

        // Companies
        Route::get('companies', CompanyIndex::class)->name('company.index');

        // Shifts
        Route::get('shifts', ShiftIndex::class)->name('shift.index');

        // Areas
        Route::get('areas', AreaIndex::class)->name('area.index');

        // Services
        Route::get('services', ServiceIndex::class)->name('service.index');

        // Settings
        Route::get('settings', SettingsForm::class)->name('settings.form');
    });
});
