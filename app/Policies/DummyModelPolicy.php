<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class DummyModelPolicy
{

    public function before(User $user, string $ability): bool|null
    {
        if ($user->id === 1) {
            return true;
        }

        return null;
    }

    // Dashboard
    public function dashboard_menu(User $user): bool
    {
        return $user->hasPermission('dashboard_menu');
    }


    // Operations
    public function operations_title(User $user): bool
    {
        return
            $user->hasPermission('customers_menu')
            || $user->hasPermission('orders_menu')
            || $user->hasPermission('marketing_menu')
            || $user->hasPermission('dispatching_menu')
            || $user->hasPermission('invoices_menu')
            // || Add More if Needed
        ;
    }
    public function canDispatch(User $user): bool
    {
        return $user->hasPermission('dispatching_menu');
    }

    // Cashier 
    public function cashier_title(User $user)
    {
        return
            $user->hasPermission('cash_collection_menu')
            || $user->hasPermission('knet_collection_menu')
            || $user->hasPermission('part_invoices_menu')
            // || Add More if Needed
        ;
    }

    public function cash_collection_menu(User $user)
    {
        return $user->hasPermission('cash_collection_menu');
    }

    public function knet_collection_menu(User $user)
    {
        return $user->hasPermission('knet_collection_menu');
    }





    // Accounting
    public function accounting_title(User $user): bool
    {
        return
            $user->hasPermission('accounts_menu')
            || $user->hasPermission('journal_vouchers_menu')
            || $this->accounting_reports($user);
            // || Add More if Needed
        ;
    }
    public function accounting_reports(User $user): bool
    {
        return
            $user->hasPermission('account_statement_report')
            || $user->hasPermission('balance_sheet_report')
            || $user->hasPermission('trial_balance_report')
            || $user->hasPermission('profit_loss_report');
    }
    public function account_statement_report(User $user): bool
    {
        return $user->hasPermission('account_statement_report');
    }
    public function balance_sheet_report(User $user): bool
    {
        return $user->hasPermission('balance_sheet_report');
    }
    public function trial_balance_report(User $user): bool
    {
        return $user->hasPermission('trial_balance_report');
    }
    public function profit_loss_report(User $user): bool
    {
        return $user->hasPermission('profit_loss_report');
    }

    public function admin_title(User $user): bool
    {
        return
            $user->hasPermission('suppliers_menu')
            || $user->hasPermission('const_centers_menu')
            || $user->hasPermission('roles_menu')
            || $user->hasPermission('users_menu')
            || $user->hasPermission('titles_menu')
            || $user->hasPermission('statuses_menu')
            || $user->hasPermission('departments_menu')
            || $user->hasPermission('companies_menu')
            || $user->hasPermission('shifts_menu')
            || $user->hasPermission('areas_menu')
            || $user->hasPermission('services_menu')
            || $user->hasPermission('settings_menu')
            // || Add More if Needed
        ;
    }

    public function assets_title(User $user): bool
    {
        return
            $user->hasPermission('cars_menu')
            // || Add More if Needed
        ;
    }

    public function hr_title(User $user): bool
    {
        return
            $user->hasPermission('employees_menu')
            // || Add More if Needed
        ;
    }
}
