<?php

namespace App\Providers;

use App\Models\Account;
use App\Models\Area;
use App\Models\Car;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\Marketing;
use App\Models\Order;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Shift;
use App\Models\Status;
use App\Models\Title;
use App\Models\User;
use App\Policies\AccountPolicy;
use App\Policies\AreaPolicy;
use App\Policies\CarPolicy;
use App\Policies\CompanyPolicy;
use App\Policies\CustomerPolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\EmployeePolicy;
use App\Policies\InvoicePolicy;
use App\Policies\MarketingPolicy;
use App\Policies\OrderPolicy;
use App\Policies\RolePolicy;
use App\Policies\ServicePolicy;
use App\Policies\SettingPolicy;
use App\Policies\ShiftPolicy;
use App\Policies\StatusPolicy;
use App\Policies\TitlePolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;


// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Order::class => OrderPolicy::class,
        Customer::class => CustomerPolicy::class,
        Marketing::class => MarketingPolicy::class,
        Account::class => AccountPolicy::class,
        Invoice::class => InvoicePolicy::class,
        Employee::class => EmployeePolicy::class,
        Car::class => CarPolicy::class,
        Role::class => RolePolicy::class,
        User::class => UserPolicy::class,
        Title::class => TitlePolicy::class,
        Status::class => StatusPolicy::class,
        Department::class => DepartmentPolicy::class,
        Company::class => CompanyPolicy::class,
        Shift::class => ShiftPolicy::class,
        Area::class => AreaPolicy::class,
        Service::class => ServicePolicy::class,
        Setting::class => SettingPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('dashboard_menu', function($user) {
            return true;
        });

        Gate::define('dispatching_menu', function($user) {
            return true;
        });

        Gate::define('operations_reports', function($user) {
            return true;
        });

        Gate::define('journal_vouchers_menu', function($user) {
            return true;
        });

        Gate::define('bank_payments_menu', function($user) {
            return true;
        });

        Gate::define('bank_receipts_menu', function($user) {
            return true;
        });

        Gate::define('accounting_reports', function($user) {
            return true;
        });

        Gate::define('hr_reports', function($user) {
            return true;
        });

        Gate::define('assets_reports', function($user) {
            return true;
        });

        // if (Schema::hasTable('permissions')) {
        //     $permissions = Permission::pluck('id', 'name');
        //     foreach ($permissions as $Permission_name => $permission_id) {
        //         Gate::define($Permission_name, function ($user) use ($permission_id) {
        //             return $user->hasPermission($permission_id);
        //         });
        //     }
        // }
    }
}
