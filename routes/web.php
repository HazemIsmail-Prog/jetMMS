<?php

use App\Http\Controllers\DashboardController;
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
use App\Http\Controllers\LetterController;
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
use App\Http\Controllers\ProfitLossController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DispatchingPageController;
use App\Http\Controllers\TechnicianPageController;
use App\Http\Controllers\CustomerContractController;
use App\Http\Middleware\NoTechnicians;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\ReconciliationController;




Route::get('lang/{lang}', [LanguageController::class, 'switchLang'])->name('lang.swith');

Route::get('cancel-survey/{encryptedOrderId}',[SurveyController::class, 'cancelSurveyPage'])->name('cancel.survey.page');
Route::post('cancel-survey/{order}',[SurveyController::class, 'storeCancelSurvey']);
Route::get('customer-page/{encryptedOrderId}',CustomerPage::class)->name('customer.page');

Route::get('/invoice/pdf/{encryptedInvoiceId}', [InvoiceController::class, 'pdf'])->name('invoice.pdf');
Route::get('/invoice/detailed_pdf/{encryptedInvoiceId}', [InvoiceController::class, 'detailed_pdf'])->name('invoice.detailed_pdf');


Route::middleware([
    'auth:sanctum',
    'active',
    config('jetstream.auth_session'),
    'verified',
    ])->group(function () {
        
        // Technician Page (new routes for Alpine JS Version)
        Route::get('/technicianPage', [TechnicianPageController::class,'index'])->name('technicianPage.index');
        Route::get('/technicianPage/getCurrentOrderForTechnician', [TechnicianPageController::class,'getCurrentOrderForTechnician']);   

        // Technician Page (old routes for Livewire Version)
        // Route::get('/technician_page', TechnicianPage::class)->name('technician_page');




        Route::group(['middleware' => 'no_technicians'], function () {

            // Activity Log
            Route::get('/activityLog', ActivityLogController::class)->name('actions-log.index');

            // ========== Dashboard ==========
            Route::get('/',[DashboardController::class,'index'])->name('dashboard')->can('dashboard_menu', DummyModel::class);
            Route::get('/ordersChart/{year}',[DashboardController::class,'ordersChart']);
            Route::get('/customersChart/{year}',[DashboardController::class,'customersChart']);
            Route::get('/technicianCompletionAverage/{year}',[DashboardController::class,'technicianCompletionAverage']);
            Route::get('/dailyStatistics',[DashboardController::class,'dailyStatistics']);
            Route::get('/ordersStatusCounter',[DashboardController::class,'ordersStatusCounter']);
            Route::get('/marketingCounter',[DashboardController::class,'marketingCounter']);
            Route::get('/deletedInvoices',[DashboardController::class,'deletedInvoices']);
            Route::get('/departmentTechnicianCounter',[DashboardController::class,'departmentTechnicianCounter']);
            Route::get('/customersWithNoOrders/{year}', [DashboardController::class, 'customersWithNoOrders']);
            Route::get('/customersCompletedOrdersStatistics/{year}/{operator}/{count}', [DashboardController::class, 'customersCompletedOrdersStatistics']);

            Route::get('alerts',AlertIndex::class)->name('alerts')->can('alerts_menu',DummyModel::class);


            Route::apiResource('attachments', AttachmentController::class);

            // ========== Operations ==========


            // Customers (new routes for Alpine JS Version)
            Route::get('/customers/{customer}/getAllOrders', [CustomerController::class, 'getAllOrders']);
            Route::get('/customers/{customer}/getInProgressOrders', [CustomerController::class, 'getInProgressOrders']);
            Route::get('/customers/{customer}/getDepartmentInProgressOrders/{department}', [CustomerController::class, 'getDepartmentInProgressOrders']);
            Route::get('/customers/getAvailableTechnicians/{department}', [CustomerController::class, 'getAvailableTechnicians']);
            Route::apiResource('/customers', CustomerController::class);

            // Customers (old routes for Livewire Version)
            // Route::get('customers', CustomerIndex::class)
            //     ->name('customer.index')
            //     ->can('viewAny', Customer::class);


            // Orders (new routes for Alpine JS Version)
            Route::controller(OrderController::class)->group(function () {
                Route::get      ('/orders/exportToExcel', 'exportToExcel');
                Route::get      ('/orders', 'index')->name('orders.index');
                Route::post     ('/orders/{customer}', 'store');
                Route::put      ('/orders/{order}', 'update');
                Route::get      ('/orders/{order}', 'show');
                Route::put      ('/orders/{order}/changeTechnician', 'changeTechnician');
                Route::put      ('/orders/{order}/setPending', 'setPending');
                Route::put      ('/orders/{order}/changeIndex', 'changeIndex');
                Route::put      ('/orders/{order}/setHold', 'setHold');
                Route::put      ('/orders/{order}/setCancelled', 'setCancelled');
                Route::put      ('/orders/{order}/setReceived', 'setReceived')->withoutMiddleware(NoTechnicians::class);
                Route::put      ('/orders/{order}/setArrived', 'setArrived')->withoutMiddleware(NoTechnicians::class);
                Route::put      ('/orders/{order}/setCompleted', 'setCompleted')->withoutMiddleware(NoTechnicians::class);
                Route::get      ('/orders/{order}/getOrderStatuses', 'getOrderStatuses');

                // Invoices
                Route::get      ('/orders/{order}/invoices','getInvoices')->withoutMiddleware(NoTechnicians::class);
                Route::post     ('/orders/{order}/invoices','storeInvoice')->withoutMiddleware(NoTechnicians::class);
                Route::put      ('/orders/{order}/invoices/{invoice}','updateInvoice'); // for discount
                Route::get      ('/orders/{order}/invoices/{invoice}','showInvoice')->withoutMiddleware(NoTechnicians::class);
                Route::delete   ('/orders/{order}/invoices/{invoice}','destroyInvoice');

                // Payments
                Route::get      ('/orders/{order}/invoices/{invoice}/payments','getPayments')->withoutMiddleware(NoTechnicians::class);
                Route::post     ('/orders/{order}/invoices/{invoice}/payments','storePayment')->withoutMiddleware(NoTechnicians::class);
                Route::get      ('/orders/{order}/invoices/{invoice}/payments/{payment}','showPayment')->withoutMiddleware(NoTechnicians::class);
                Route::delete   ('/orders/{order}/invoices/{invoice}/payments/{payment}','destroyPayment');

                // Comments
                Route::get      ('/orders/{order}/comments','getComments')->withoutMiddleware(NoTechnicians::class);
                Route::post     ('/orders/{order}/comments','storeComment')->withoutMiddleware(NoTechnicians::class);
                Route::get      ('/orders/{order}/comments/{comment}','showComment')->withoutMiddleware(NoTechnicians::class);


                // get department services for invoice form
                Route::get      ('/orders/{order}/getDepartmentServices', 'getDepartmentServices')->withoutMiddleware(NoTechnicians::class);
            });

            Route::post('/send-multiple-survey-message', [SurveyController::class, 'sendMultipleSurveyMessage']);
            Route::get('cancel-surveys', [SurveyController::class, 'cancelSurveyIndex'])->name('cancel-surveys.index');


            // Orders (old routes for Livewire Version)
            // Route::get('orders', OrderIndex::class)
            //     ->name('order.index')
            //     ->can('viewAny', Order::class);

            Route::get('marketings', MarketingIndex::class)
                ->name('marketing.index')
                ->can('viewAny', Marketing::class);

            Route::get('ratings', RatingIndex::class)
                ->name('rating.index')
                ->can('viewAny', Rating::class);



            // Dispatching (New routes for Alpine JS Version)
            Route::get('/dispatching/{department}', [DispatchingPageController::class,'index'])->name('dispatch-panel.index');
            Route::get('/dispatching/getTodaysCompletedOrdersForTechnician/{user}', [DispatchingPageController::class,'getTodaysCompletedOrdersForTechnician']);

            // Dispatching (old routes for Livewire Version)
            // Route::get('dispatch-panel/{department}', DispatchingIndex::class)
            //     ->name('dispatch-panel.index')
            //     ->can('canDispatch', DummyModel::class);

            // I Temporary disabled this route because it's not used for now
            // Route::get('operations/reports/expected_invoices_deletion', ExpectedInvoicesDeletion::class)
            //     ->name('expected_invoices_deletion')
            //     ->can('expected_invoices_deletion_report', DummyModel::class);




            // ========== Contracts ==========

            // New routes for Alpine JS Version
            Route::post('/createCustomerContract/{customer}', [CustomerContractController::class,'createCustomerContract']);


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
            Route::get('accounts/reports/daily_review/exportToExcel', [ReportController::class, 'daily_review_exportToExcel'])->can('daily_review_report', DummyModel::class);

            Route::get('accounts/reports/daily_review', [ReportController::class, 'daily_review'])
                ->name('daily_review')
                ->can('daily_review_report', DummyModel::class)
                ;

            // Route::get('accounts/reports/daily_review', DailyReview::class)
            //     ->name('daily_review')
            //     ->can('daily_review_report', DummyModel::class)
            //     ;

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


            Route::get('accounts/reports/trial_balance', [ReportController::class, 'trial_balance'])
                ->name('trial_balance');

            // Route::get('accounts/reports/trial_balance', TrialBalance::class)
            //     ->name('trial_balance')
            //     ->can('trial_balance_report', DummyModel::class);

            Route::get('accounts/reports/profit_loss', [ProfitLossController::class, 'index']) // TODO:change class to its new page
                ->name('profit_loss')
                ->can('profit_loss_report', DummyModel::class);

            // Invoices
            Route::get('invoices/report',[ReportController::class, 'invoices'])->name('invoice.report');
            Route::get('invoices/report/getData',[ReportController::class, 'getData']);

            // new routes for Alpine JS Version
            Route::get('invoices/exportToExcel', [InvoiceController::class, 'exportToExcel']);
            Route::get('invoices', [InvoiceController::class, 'index'])->name('invoice.index');
            // old routes for Livewire Version
            // Route::get('invoices', InvoiceIndex::class)
            //     ->name('invoice.index')
            //     ->can('viewAny', Invoice::class);


            Route::post('reconciliations/{invoice}', [ReconciliationController::class, 'store']);

            // Vouchers
            Route::get('vouchers', [VoucherController::class,'index'])->name('vouchers.index');
            Route::post('vouchers', [VoucherController::class,'store']);
            Route::put('vouchers/{voucher}', [VoucherController::class,'update']);
            Route::get('getVoucherDetails/{voucher}', [VoucherController::class,'getVoucherDetails']);

            // Vouchers (old with Livewire)
            // Route::get('vouchers', VoucherIndex::class)
            //     ->name('voucher.index')
            //     ->can('viewAny', Voucher::class);


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

            Route::apiResource('letters', LetterController::class);


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

            // Users
            // new routes for Alpine JS Version
            Route::controller(UserController::class)->group(function () {
                Route::get('users/generate-username', 'generateUsername');
                Route::put('users/{user}/change-status', 'changeStatus');
                Route::post('users', 'store');
                Route::put('users/{user}', 'update');
                Route::delete('users/{user}', 'destroy');
                Route::get('users/{user}', 'show');
                Route::get('users', 'index')->name('users.index');
            });

            // Users (old routes for Livewire Version)
            // Route::get('users', UserIndex::class)
            //     ->name('user.index')
            //     ->can('viewAny', User::class);

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
