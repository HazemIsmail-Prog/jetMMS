<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LanguageController;
use App\Livewire\AccountingReports\AccountStatement;
use App\Livewire\AccountingReports\CollectionStatement;
use App\Livewire\AccountingReports\DailyReview;
use App\Livewire\AccountingReports\PendingPayments;
use App\Livewire\AccountingReports\ShiftTargetStatement;
use App\Livewire\AccountingReports\TargetStatement;
use App\Livewire\AccountingReports\TrialBalance;
use App\Livewire\AccountingReports\UsersReceivables;
use App\Livewire\Accounts\AccountIndex;
use App\Livewire\Alerts\AlertIndex;
use App\Livewire\Areas\AreaIndex;
use App\Livewire\Cars\CarIndex;
use App\Livewire\Cashier\CashCollection;
use App\Livewire\Cashier\KnetCollection;
use App\Livewire\Companies\Budgets\BudgetIndex;
use App\Livewire\Companies\CompanyIndex;
use App\Livewire\Companies\Contracts\ContractIndex as ContractsContractIndex;
use App\Livewire\Contracts\ContractIndex;
use App\Livewire\CostCenters\CostCenterIndex;
use App\Livewire\CustomerPage;
use App\Livewire\Customers\CustomerIndex;
use App\Livewire\Departments\DepartmentIndex;
use App\Livewire\Dispatching\DispatchingIndex;
use App\Livewire\Documents\DocumentIndex;
use App\Livewire\Documents\DocumentTypeIndex;
use App\Livewire\Employees\EmployeeIndex;
use App\Livewire\Invoices\InvoiceIndex;
use App\Livewire\Marketing\MarketingIndex;
use App\Livewire\OperationsReports\ExpectedInvoicesDeletion;
use App\Livewire\Orders\OrderIndex;
use App\Livewire\PartInvoices\PartInvoiceIndex;
use App\Livewire\Permissions\PermissionIndex;
use App\Livewire\PhoneDevices\DeviceIndex;
use App\Livewire\Quotations\QuotationIndex;
use App\Livewire\Ratings\RatingIndex;
use App\Livewire\Roles\RoleIndex;
use App\Livewire\Services\ServiceIndex;
use App\Livewire\Settings\SettingsForm;
use App\Livewire\Shifts\ShiftIndex;
use App\Livewire\Statuses\StatusIndex;
use App\Livewire\Suppliers\SupplierIndex;
use App\Livewire\Targets\TargetForm;
use App\Livewire\TechnicianPage;
use App\Livewire\Titles\TitleIndex;
use App\Livewire\Users\UserIndex;
use App\Livewire\Vouchers\VoucherIndex;
use App\Models\Account;
use App\Models\Area;
use App\Models\Car;
use App\Models\Company;
use App\Models\CompanyBudget;
use App\Models\CompanyContract;
use App\Models\Contract;
use App\Models\CostCenter;
use App\Models\Customer;
use App\Models\Department;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\DummyModel;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\Marketing;
use App\Models\Order;
use App\Models\PartInvoice;
use App\Models\Permission;
use App\Models\PhoneDevice;
use App\Models\Quotation;
use App\Models\Rating;
use App\Models\Role;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Shift;
use App\Models\Status;
use App\Models\Supplier;
use App\Models\Title;
use App\Models\User;
use App\Models\Voucher;
use App\Policies\ContractPolicy;
use Illuminate\Support\Facades\Route;

Route::get('lang/{lang}', [LanguageController::class, 'switchLang'])->name('lang.swith');

Route::get('customer-page/{encryptedOrderId}',CustomerPage::class)->name('customer.page');

Route::get('/invoice/pdf/{encryptedInvoiceId}', [InvoiceController::class, 'pdf'])->name('invoice.pdf');
Route::get('/invoice/detailed_pdf/{encryptedInvoiceId}', [InvoiceController::class, 'detailed_pdf'])->name('invoice.detailed_pdf');


Route::middleware([
    'auth:sanctum',
    'active',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/technician_page', TechnicianPage::class)->name('technician_page');




    Route::group(['middleware' => 'no_technicians'], function () {

        // ========== Dashboard ==========
        Route::get('/', function () {
            return view('dashboard');
        })->name('dashboard')->can('dashboard_menu', DummyModel::class);

        Route::get('alerts',AlertIndex::class)->name('alerts')->can('alerts_menu',DummyModel::class);

        // ========== Operations ==========
        Route::get('customers', CustomerIndex::class)
            ->name('customer.index')
            ->can('viewAny', Customer::class);

        Route::get('orders', OrderIndex::class)
            ->name('order.index')
            ->can('viewAny', Order::class);

        Route::get('marketings', MarketingIndex::class)
            ->name('marketing.index')
            ->can('viewAny', Marketing::class);

        Route::get('ratings', RatingIndex::class)
            ->name('rating.index')
            ->can('viewAny', Rating::class);

        Route::get('dispatch-panel/{department}', DispatchingIndex::class)
            ->name('dispatch-panel.index')
            ->can('canDispatch', DummyModel::class);

        Route::get('operations/reports/expected_invoices_deletion', ExpectedInvoicesDeletion::class)
            ->name('expected_invoices_deletion')
            ->can('expected_invoices_deletion_report', DummyModel::class);




        // ========== Contracts ==========

        Route::get('construction-contracts',ContractIndex::class)
            ->name('construction.contracts')
            ->can('viewConstructionContracts',Contract::class);

        Route::get('subscription-contracts',ContractIndex::class)
            ->name('subscription.contracts')
            ->can('viewSubscriptionContracts',Contract::class);

        Route::get('quotations',QuotationIndex::class)
            ->name('quotations')
            ->can('viewAny',Quotation::class);



        // ========== Cashier ==========

        Route::get('cash-collection', CashCollection::class)
            ->name('cash_collection')
            ->can('cash_collection_menu', DummyModel::class);

        Route::get('knet-collection', KnetCollection::class)
            ->name('knet_collection')
            ->can('knet_collection_menu', DummyModel::class);

        Route::get('part-invoices', PartInvoiceIndex::class)
            ->name('part_invoice')
            ->can('viewAny', PartInvoice::class);

        Route::get('targets',TargetForm::class)
            ->name('targets')
            ->can('targets_menu', DummyModel::class);

        // ========== Accounting ==========

        // Accounts
        Route::get('accounts', AccountIndex::class)
            ->name('account.index')
            ->can('viewAny', Account::class);

        Route::get('accounts/reports/daily_review', DailyReview::class)
            ->name('daily_review')
            ->can('daily_review_report', DummyModel::class)
            ;

        Route::get('accounts/reports/collection_statement', CollectionStatement::class)
            ->name('collection_statement')
            ->can('collection_statement_report', DummyModel::class)
            ;

        Route::get('accounts/reports/target_statement', TargetStatement::class)
            ->name('target_statement')
            ->can('target_statement_report', DummyModel::class)
            ;
        Route::get('accounts/reports/shift_target_statement', ShiftTargetStatement::class)
            ->name('shift_target_statement')
            ->can('shift_target_statement_report', DummyModel::class)
            ;

        Route::get('accounts/reports/users_receivables', UsersReceivables::class)
            ->name('users_receivables')
            ->can('users_receivables_report', DummyModel::class)
            ;

        Route::get('accounts/reports/pending_payments', PendingPayments::class)
            ->name('pending_payments')
            ->can('pending_payments_report', DummyModel::class)
            ;

        Route::get('accounts/reports/account_statement', AccountStatement::class)
            ->name('account_statement')
            ->can('account_statement_report', DummyModel::class);

        Route::get('accounts/reports/balance_sheet', AccountStatement::class) // TODO:change class to its new page
            ->name('balance_sheet')
            ->can('balance_sheet_report', DummyModel::class);

        Route::get('accounts/reports/trial_balance', TrialBalance::class)
            ->name('trial_balance')
            ->can('trial_balance_report', DummyModel::class);

        Route::get('accounts/reports/profit_loss', AccountStatement::class) // TODO:change class to its new page
            ->name('profit_loss')
            ->can('profit_loss_report', DummyModel::class);

        // Invoices
        Route::get('invoices', InvoiceIndex::class)
            ->name('invoice.index')
            ->can('viewAny', Invoice::class);

        // Vouchers
        Route::get('vouchers', VoucherIndex::class)
            ->name('voucher.index')
            ->can('viewAny', Voucher::class);


        // ========== HR ==========

        // Employees
        Route::get('employees', EmployeeIndex::class)
            ->name('employee.index')
            ->can('viewAny', Employee::class);

        // ========== Assets ==========
        Route::get('cars', CarIndex::class)
            ->name('car.index')
            ->can('viewAny', Car::class);

        Route::get('phone_devices', DeviceIndex::class)
        ->name('phone_device.index')
        ->can('viewAny', PhoneDevice::class);

        Route::get('document_types', DocumentTypeIndex::class)
        ->name('document_type.index')
        ->can('viewAny', DocumentType::class);

        Route::get('documents', DocumentIndex::class)
        ->name('document.index')
        ->can('viewAny', Document::class);

        // ========== Administration ==========
        Route::get('company_contracts', ContractsContractIndex::class)
            ->name('company_contract.index')
            ->can('viewAny', CompanyContract::class);

        Route::get('company_budgets', BudgetIndex::class)
            ->name('company_budget.index')
            ->can('viewAny', CompanyBudget::class);

        // ========== Admin Settings ==========
        Route::get('suppliers', SupplierIndex::class)
            ->name('supplier.index')
            ->can('viewAny', Supplier::class);

        Route::get('cost_centers', CostCenterIndex::class)
            ->name('cost_center.index')
            ->can('viewAny', CostCenter::class);

        Route::get('roles', RoleIndex::class)
            ->name('role.index')
            ->can('viewAny', Role::class);

        Route::get('permissions', PermissionIndex::class)
            ->name('permission.index')
            ->can('viewAny', Permission::class);

        Route::get('users', UserIndex::class)
            ->name('user.index')
            ->can('viewAny', User::class);

        Route::get('titles', TitleIndex::class)
            ->name('title.index')
            ->can('viewAny', Title::class);

        Route::get('statuses', StatusIndex::class)
            ->name('status.index')
            ->can('viewAny', Status::class);

        Route::get('departments', DepartmentIndex::class)
            ->name('department.index')
            ->can('viewAny', Department::class);

        Route::get('companies', CompanyIndex::class)
            ->name('company.index')
            ->can('viewAny', Company::class);

        Route::get('shifts', ShiftIndex::class)
            ->name('shift.index')
            ->can('viewAny', Shift::class);

        Route::get('areas', AreaIndex::class)
            ->name('area.index')
            ->can('viewAny', Area::class);

        Route::get('services', ServiceIndex::class)
            ->name('service.index')
            ->can('viewAny', Service::class);

        Route::get('settings', SettingsForm::class)
            ->name('settings.form')
            ->can('viewAny', Setting::class);
    });
});
