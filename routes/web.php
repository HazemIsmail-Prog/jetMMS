<?php

use App\Http\Controllers\LanguageController;
use App\Livewire\Areas\AreaIndex;
use App\Livewire\Companies\CompanyIndex;
use App\Livewire\Customers\CustomerForm;
use App\Livewire\Customers\CustomerIndex;
use App\Livewire\Dispatching\DispatchingIndex;
use App\Livewire\Employees\EmployeeForm;
use App\Livewire\Employees\EmployeeIndex;
use App\Livewire\Fleet\ActionIndex;
use App\Livewire\Fleet\ActionReport;
use App\Livewire\Fleet\CarForm;
use App\Livewire\Fleet\CarIndex;
use App\Livewire\Marketing\MarketingIndex;
use App\Livewire\Orders\OrderForm;
use App\Livewire\Orders\OrderIndex;
use App\Livewire\Roles\RoleIndex;
use App\Livewire\Titles\TitleIndex;
use App\Livewire\Users\UserForm;
use App\Livewire\Users\UserIndex;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('lang/{lang}', [LanguageController::class, 'switchLang'])->name('lang.swith');


Route::middleware([
    'auth:sanctum',
    'active',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    
    // Dashboard
    Route::get('/', function () { return view('dashboard'); })->name('dashboard');

    //Dispatching 
    Route::get('dispatch-panel/{department}', DispatchingIndex::class)->name('dispatch-panel.index');

    // Roles
    Route::get('roles',RoleIndex::class)->name('role.index');

    
    // Users
    Route::get('users',UserIndex::class)->name('user.index');
    Route::get('users/form/{user?}',UserForm::class)->name('user.form');
    
    // Titles
    Route::get('titles',TitleIndex::class)->name('title.index');
    
    // Companies
    Route::get('companies',CompanyIndex::class)->name('company.index');
    
    // Areas
    Route::get('areas',AreaIndex::class)->name('area.index');

    // Customers
    Route::get('customers',CustomerIndex::class)->name('customer.index');
    Route::get('customers/form/{customer?}',CustomerForm::class)->name('customer.form');
    
    // Orders
    Route::get('orders',OrderIndex::class)->name('order.index');
    
    // Marketings
    Route::get('marketings',MarketingIndex::class)->name('marketing.index');

    // Employees
    Route::get('employees',EmployeeIndex::class)->name('employee.index');
    Route::get('employees/form/{employee?}',EmployeeForm::class)->name('employee.form');
    
    // Assets
    Route::get('cars',CarIndex::class)->name('car.index');
    Route::get('cars/form/{car?}',CarForm::class)->name('car.form');
    Route::get('car-actions/{car}',ActionIndex::class)->name('car.action.index');
    Route::get('car-action-report/{action}',ActionReport::class)->name('car.action.report');
});
