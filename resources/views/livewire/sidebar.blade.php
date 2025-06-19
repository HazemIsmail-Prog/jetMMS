<div>
    <div id="sidebar" x-cloak
        class="
            fixed
            start-0
            top-0
            h-full
           bg-gray-800
            z-50
            w-64
            transition-transform
            flex
            flex-col
            hidden-scrollbar
            dark:border-gray-700
            overflow-y-auto
            no-scrollbar
            shrink-0
            p-3
            duration-200
            ease-in-out
            {{ app()->getLocale() == 'ar' ? 'dark:border-l' : 'dark:border-r' }}
            "
        :class="{ '-translate-x-full': !sidebarExpanded && isLTR, 'translate-x-full': !sidebarExpanded && isRTL }">
        <!-- Sidebar header -->
        <div class="flex justify-between mb-10 pe-3 sm:px-2">
            <!-- Logo -->
            <x-application-mark class="w-6" />
        </div>
        <!-- Links -->
        <!-- Pages group -->
        <ul class="space-y-1">
            @if(auth()->id() == 1)
                <x-sidebar-item icon="truck" route="actions-log.index" :title="__('messages.actions_log')" />
            @endif
            <!-- Dashboard -->
            @can('dashboard_menu', App\Models\DummyModel::class)
                <x-sidebar-item icon="chart-pie" route="dashboard" :title="__('messages.dashboard')" />
            @endcan
            @can('alerts_menu', App\Models\DummyModel::class)
                <x-sidebar-item icon="bell" route="alerts" :title="__('messages.alerts')" />
            @endcan
            {{-- Operations --}}
            @can('operations_title', App\Models\DummyModel::class)
                <h3 class=" py-3 text-xs uppercase text-slate-500 font-semibold ps-3">{{ __('messages.operations') }}
                </h3>
            @endcan
            @can('viewAny', App\Models\Customer::class)
                <x-sidebar-item icon="users" route="customers.index" :title="__('messages.customers')" />
            @endcan
            @can('viewAny', App\Models\Order::class)
                <x-sidebar-item icon="list-bullet" route="orders.index" :title="__('messages.orders')" />
            @endcan
            @can('viewAny', App\Models\Marketing::class)
                <x-sidebar-item icon="arrow-trending-up" route="marketing.index" :title="__('messages.marketing')" />
            @endcan
            @can('viewAny', App\Models\Rating::class)
                <x-sidebar-item icon="star" route="rating.index" :title="__('messages.ratings')" />
            @endcan
            @can('viewAny', App\Models\Invoice::class)
                <x-sidebar-item icon="circle-stack" route="invoice.index" :title="__('messages.invoices')" />
            @endcan

            @can('operations_reports', App\Models\DummyModel::class)
                <x-sidebar-dropdown title="{{ __('messages.reports') }}" icon="chart-bar">
                    @can('expected_invoices_deletion_report', App\Models\DummyModel::class)
                        <x-nested-sidebar-item route="expected_invoices_deletion" :title="__('messages.expected_invoices_deletion')" />
                    @endcan
                    @can('viewReport', App\Models\Invoice::class)
                        <x-nested-sidebar-item route="invoice.report" :title="__('messages.invoices_per_technician_report')" />
                    @endcan
                </x-sidebar-dropdown>
            @endcan
            @can('canDispatch', App\Models\DummyModel::class)

                <h3 class=" py-3 text-xs uppercase text-slate-500 font-semibold ps-3">{{ __('messages.dispatching') }}
                </h3>
                @foreach ($this->departments as $department)
                    <x-sidebar-item icon="truck" route="dispatch-panel.index" :param="$department->id" :title="$department->name" />
                @endforeach

            @endcan

            {{-- Contracts --}}
            @can('contracts_title', App\Models\DummyModel::class)
                <h3 class=" py-3 text-xs uppercase text-slate-500 font-semibold ps-3">{{ __('messages.contracts') }}</h3>
            @endcan
            @can('viewConstructionContracts', App\Models\Contract::class)
                <x-sidebar-item icon="document-text" route="construction.contracts" :title="__('messages.construction_contracts')" />
            @endcan
            @can('viewSubscriptionContracts', App\Models\Contract::class)
                <x-sidebar-item icon="document-text" route="subscription.contracts" :title="__('messages.subscription_contracts')" />
            @endcan

            @can('viewAny', App\Models\Quotation::class)
                <x-sidebar-item icon="document-text" route="quotations" :title="__('messages.quotations')" />
            @endcan



            {{-- Cashier --}}
            @can('cashier_title', App\Models\DummyModel::class)
                <h3 class=" py-3 text-xs uppercase text-slate-500 font-semibold ps-3">{{ __('messages.cashier') }}</h3>
            @endcan
            @can('cash_collection_menu', App\Models\DummyModel::class)
                <x-sidebar-item icon="banknotes" route="cash_collection" :title="__('messages.cash_collection')" />
            @endcan
            @can('knet_collection_menu', App\Models\DummyModel::class)
                <x-sidebar-item icon="credit-card" route="knet_collection" :title="__('messages.knet_collection')" />
            @endcan
            @can('viewAny', App\Models\PartInvoice::class)
                <x-sidebar-item icon="document-text" route="part_invoice" :title="__('messages.part_invoices')" />
            @endcan
            @can('targets_menu', App\Models\DummyModel::class)
                <x-sidebar-item icon="banknotes" route="targets" :title="__('messages.targets')" />
            @endcan
            {{-- Accounting --}}
            @can('accounting_title', App\Models\DummyModel::class)
                <h3 class=" py-3 text-xs uppercase text-slate-500 font-semibold ps-3">{{ __('messages.accounting') }}
                </h3>
            @endcan
            @can('viewAny', App\Models\Account::class)
                <x-sidebar-item icon="bars-3-bottom-left" route="account.index" :title="__('messages.accounts')" />
            @endcan
            @can('viewAny', App\Models\Voucher::class)
                <x-sidebar-item icon="document-text" route="vouchers.index" :title="__('messages.journal_vouchers')" />
            @endcan
            @can('accounting_reports', App\Models\DummyModel::class)
                <x-sidebar-dropdown title="{{ __('messages.reports') }}" icon="chart-bar">
                    @can('daily_review_report', App\Models\DummyModel::class)
                        <x-nested-sidebar-item route="daily_review" :title="__('messages.daily_review')" />
                    @endcan
                    @can('collection_statement_report', App\Models\DummyModel::class)
                        <x-nested-sidebar-item route="collection_statement" :title="__('messages.collection_statement')" />
                    @endcan
                    @can('target_statement_report', App\Models\DummyModel::class)
                        <x-nested-sidebar-item route="target_statement" :title="__('messages.target_statement')" />
                    @endcan
                    @can('shift_target_statement_report', App\Models\DummyModel::class)
                        <x-nested-sidebar-item route="shift_target_statement" :title="__('messages.shift_target_statement')" />
                    @endcan
                    @can('users_receivables_report', App\Models\DummyModel::class)
                        <x-nested-sidebar-item route="users_receivables" :title="__('messages.users_receivables')" />
                    @endcan
                    @can('pending_payments_report', App\Models\DummyModel::class)
                        <x-nested-sidebar-item route="pending_payments" :title="__('messages.pending_invoices_report')" />
                    @endcan
                    @can('account_statement_report', App\Models\DummyModel::class)
                        <x-nested-sidebar-item route="account_statement" :title="__('messages.account_statement')" />
                    @endcan
                    @can('balance_sheet_report', App\Models\DummyModel::class)
                        <x-nested-sidebar-item route="balance_sheet" :title="__('messages.balance_sheet')" />
                    @endcan
                    @can('trial_balance_report', App\Models\DummyModel::class)
                        <x-nested-sidebar-item route="trial_balance" :title="__('messages.trial_balance')" />
                    @endcan
                    @can('profit_loss_report', App\Models\DummyModel::class)
                        <x-nested-sidebar-item route="profit_loss" :title="__('messages.profit_loss')" />
                    @endcan
                </x-sidebar-dropdown>
            @endcan
            {{-- HR --}}
            @can('hr_title', App\Models\DummyModel::class)
                <h3 class=" py-3 text-xs uppercase text-slate-500 font-semibold ps-3">{{ __('messages.hr') }}</h3>
            @endcan
            @can('viewAny', App\Models\Employee::class)
                <x-sidebar-item icon="users" route="employee.index" :title="__('messages.employees')" />
            @endcan
            @can('hr_reports')
                {{-- --}}
                {{-- --}}
                {{-- --}}
                {{-- --}}
                {{-- --}}
                {{-- --}}
                {{-- --}}
                {{-- --}}
                {{-- --}}
            @endcan
            {{-- Assets --}}
            @can('assets_title', App\Models\DummyModel::class)
                <h3 class=" py-3 text-xs uppercase text-slate-500 font-semibold ps-3">{{ __('messages.assets') }}</h3>
            @endcan
            @can('viewAny', App\Models\Car::class)
                <x-sidebar-item icon="truck" route="car.index" :title="__('messages.cars')" />
            @endcan
            @can('viewAny', App\Models\PhoneDevice::class)
                <x-sidebar-item icon="truck" route="phone_device.index" :title="__('messages.phone_devices')" />
            @endcan
            @can('viewAny', App\Models\DocumentType::class)
                <x-sidebar-item icon="truck" route="document_type.index" :title="__('messages.document_types')" />
            @endcan
            @can('viewAny', App\Models\Document::class)
                <x-sidebar-item icon="truck" route="document.index" :title="__('messages.documents')" />
            @endcan
            @can('assets_reports')
                {{-- --}}
                {{-- --}}
                {{-- --}}
                {{-- --}}
                {{-- --}}
                {{-- --}}
                {{-- --}}
                {{-- --}}
                {{-- --}}
                {{-- --}}
            @endcan



            {{-- Administration --}}
            @can('administration_title', App\Models\DummyModel::class)
                <h3 class=" py-3 text-xs uppercase text-slate-500 font-semibold ps-3">{{ __('messages.administration') }}
                </h3>
            @endcan
            @can('viewAny', App\Models\CompanyContract::class)
                <x-sidebar-item icon="truck" route="company_contract.index" :title="__('messages.company_contracts')" />
            @endcan
            @can('viewAny', App\Models\CompanyBudget::class)
                <x-sidebar-item icon="truck" route="company_budget.index" :title="__('messages.company_budgets')" />
            @endcan
            @can('viewAny', App\Models\Letter::class)
                <x-sidebar-item icon="document-text" route="letters.index" :title="__('messages.letters')" />
            @endcan





            {{-- Admin --}}
            @can('admin_title', App\Models\DummyModel::class)
                <h3 class=" py-3 text-xs uppercase text-slate-500 font-semibold ps-3">{{ __('messages.admin') }}</h3>
            @endcan
            @can('viewAny', App\Models\Supplier::class)
                <x-sidebar-item icon="chart-pie" route="supplier.index" :title="__('messages.suppliers')" />
            @endcan
            @can('viewAny', App\Models\CostCenter::class)
                <x-sidebar-item icon="chart-pie" route="cost_center.index" :title="__('messages.cost_centers')" />
            @endcan
            @can('viewAny', App\Models\Role::class)
                <x-sidebar-item icon="chart-pie" route="role.index" :title="__('messages.roles')" />
            @endcan
            @can('viewAny', App\Models\Permission::class)
                <x-sidebar-item icon="chart-pie" route="permission.index" :title="__('messages.permissions')" />
            @endcan
            @can('viewAny', App\Models\User::class)
                <x-sidebar-item icon="users" route="user.index" :title="__('messages.users')" />
            @endcan
            @can('viewAny', App\Models\Title::class)
                <x-sidebar-item icon="chart-pie" route="title.index" :title="__('messages.titles')" />
            @endcan
            @can('viewAny', App\Models\Status::class)
                <x-sidebar-item icon="chart-pie" route="status.index" :title="__('messages.statuses')" />
            @endcan
            @can('viewAny', App\Models\Department::class)
                <x-sidebar-item icon="chart-pie" route="department.index" :title="__('messages.departments')" />
            @endcan
            @can('viewAny', App\Models\Company::class)
                <x-sidebar-item icon="chart-pie" route="company.index" :title="__('messages.companies')" />
            @endcan
            @can('viewAny', App\Models\Shift::class)
                <x-sidebar-item icon="chart-pie" route="shift.index" :title="__('messages.shifts')" />
            @endcan
            @can('viewAny', App\Models\Area::class)
                <x-sidebar-item icon="chart-pie" route="area.index" :title="__('messages.areas')" />
            @endcan
            @can('viewAny', App\Models\Service::class)
                <x-sidebar-item icon="chart-pie" route="service.index" :title="__('messages.services')" />
            @endcan
            @can('viewAny', App\Models\Setting::class)
                <x-sidebar-item icon="settings" route="settings.form" :title="__('messages.settings')" />
            @endcan
        </ul>
    </div>
    {{-- Overlay --}}
    <div x-cloak @click="sidebarExpanded=false" class="md:hidden  fixed start-0 top-0 w-full h-full bg-black/50 z-40"
        :class="{ 'hidden': !sidebarExpanded }"></div>
</div>


{{-- @script --}}
{{-- this script to keep sidebar scroll position on change pages --}}
{{-- <script>
    setTimeout(function() {
                let sidebar = document.getElementById("sidebar");
                let top = localStorage.getItem("sidebar-scroll");
                if (top !== null) {
                    sidebar.scrollTop = parseInt(top, 10);
                }

                window.addEventListener("beforeunload", () => {
                    localStorage.setItem("sidebar-scroll", sidebar.scrollTop);
                });
            }, 10)
</script> --}}
{{-- @endscript --}}
