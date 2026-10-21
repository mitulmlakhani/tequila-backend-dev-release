<div class="sidebar" id="sidebar-width">
    <div class="logo-details">
    </div>
    <ul class="nav-links">
        <li>
            <a href="{{ route('restaurant.dashboard') }}" aria-hidden="true">
                <i class="bx bx-grid-alt"></i>
                <span class="link_name">Dashboard</span>
            </a>
            <ul class="sub-menu blank">
                <li><a class="link_name" href="{{ route('restaurant.dashboard') }}">Dashboard</a></li>
            </ul>
        </li>
        @if (Auth::user()->user_type == 1)
                    @if (
                Gate::check('restaurant-list') ||
                Gate::check('restaurant-create') ||
                Gate::check('restaurant-edit') ||
                Gate::check('restaurant-delete')
            )
                        <li>
                            <a href="{{ route('restaurant-list') }}" aria-hidden="true">
                                <i class="bx bx-restaurant"></i>
                                <span class="link_name">Restaurant Management</span>
                            </a>
                        </li>
                    @endif
                    @if (
                            Gate::check('admin-role-list') ||
                            Gate::check('admin-role-create') ||
                            Gate::check('admin-role-edit') ||
                            Gate::check('admin-role-delete')
                        )
                                    <li>
                                        <div class="iocn-link">
                                            <a href="#" aria-hidden="true">
                                                <i class="bx bx-user"></i>
                                                <span class="link_name "> User Roles and Permission</span>
                                            </a>
                                            <i class="bx bxs-chevron-down arrow"></i>
                                        </div>
                                        <ul class="sub-menu">
                                            <li><a class="link_name" href="#">User Roles and Permission</a></li>
                                            @if (Gate::check('user-list') || Gate::check('user-create') || Gate::check('user-edit') || Gate::check('user-delete'))
                                                <li><a href="{{ route('sa.user-list') }}">User/staff Management</a></li>
                                            @endif
                                            @if (
                                Gate::check('admin-role-list') ||
                                Gate::check('admin-role-create') ||
                                Gate::check('admin-role-edit') ||
                                Gate::check('admin-role-delete')
                            )
                                                <li><a href="{{ route('admin-role-list') }}">Role Management</a></li>
                                            @endif
                                            @if (Gate::check('role-permission-view') || Gate::check('role-permission-store'))
                                                <li><a href="{{ route('permission') }}"> Role Permission Management</a></li>
                                            @endif
                                            <li><a href="{{ route('default-role-permission.index') }}">Dafault Permission Management</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="{{ route('payment-method-list') }}" aria-hidden="true">
                                            <i class="bx bx-money"></i>
                                            <span class="link_name">Payment Methods</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('super-admin.menu.management') }}" aria-hidden="true">
                                            <i class="bx bx-store-alt"></i>
                                            <span class="link_name">Menu Management</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('floor-item-icons.index') }}" aria-hidden="true">
                                            <i class="bx bx-table"></i>
                                            <span class="link_name">Item Icons</span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="iocn-link">
                                            <a href="#" aria-hidden="true">
                                                <i class="bx bx-cog"></i>
                                                <span class="link_name"> Devices Management</span>
                                            </a>
                                            <i class="bx bxs-chevron-down arrow"></i>
                                        </div>
                                        <ul class="sub-menu">
                                            <li><a href="{{ route('super-admin.pos-devices.index') }}" aria-hidden="true">Devices Management</a></li>
                                        </ul>
                                        <ul class="sub-menu">
                                            <li><a href="{{ route('super-admin.terminal-settings.index') }}" aria-hidden="true">Terminal Settings</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <div class="iocn-link">
                                            <a href="#" aria-hidden="true">
                                                <i class="bx bx-cog"></i>
                                                <span class="link_name"> Settings</span>
                                            </a>
                                            <i class="bx bxs-chevron-down arrow"></i>
                                        </div>
                                        <ul class="sub-menu">
                                            <li><a href="{{ route('admin_settings.index') }}" aria-hidden="true">Payment Settings</a></li>
                                            <li><a href="{{ route('timezone-list') }}" aria-hidden="true">Timezone Settings</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <div class="iocn-link">
                                            <a href="#" aria-hidden="true">
                                                <i class="bx bx-cog"></i>
                                                <span class="link_name"> Billing</span>
                                            </a>
                                            <i class="bx bxs-chevron-down arrow"></i>
                                        </div>
                                        <ul class="sub-menu">
                                            <li><a href="{{ route('pos-feature-list') }}" aria-hidden="true">POS Features</a></li>
                                        </ul>
                                    </li>
                    @endif

        @endif

        @if (Auth::user()->user_type == 2)
                    @if(!empty(Auth::user()->restaurant->is_multi_branch))
                    <li>
                        <div class="iocn-link">
                            <a href="#" aria-hidden="true">
                                <i class="bx bx-layout"></i>
                                <span class="link_name">Location Management</span>
                            </a>
                            <i class="bx bxs-chevron-down arrow"></i>
                        </div>
                        <ul class="sub-menu">
                            @canany(['floor-list', 'floor-create', 'floor-edit', 'floor-delete'])
                                <li><a href="{{ route('branch-list') }}">Locations</a></li>
                            @endcanany
                        </ul>
                    </li>
                    @endif
                    @canany(['floor-list', 'floor-create', 'floor-edit', 'floor-delete'])
                        <li>
                            <div class="iocn-link">
                                <a href="#" aria-hidden="true">
                                    <i class="bx bx-layout"></i>
                                    <span class="link_name">Floor Management</span>
                                </a>
                                <i class="bx bxs-chevron-down arrow"></i>
                            </div>
                            <ul class="sub-menu">
                                @canany(['floor-list', 'floor-create', 'floor-edit', 'floor-delete'])
                                    <li><a href="{{ route('floor-list') }}">Floors</a></li>
                                @endcanany
                                @canany(['table-list', 'table-create', 'table-edit', 'table-delete'])
                                    <li><a href="{{ route('table-list') }}">Elements</a></li>
                                @endcanany

                                {{-- <li><a href="{{ url('floor-layout') }}">Floor Layout</a></li> --}}
                            </ul>
                        </li>
                @endcanany
                @canany(['table-list', 'table-create', 'table-edit', 'table-delete'])
                    <li class="d-none">
                        <div class="iocn-link">
                            <a href="#" aria-hidden="true">
                                <i class="bx bx-layout"></i>
                                <span class="link_name">Table Management</span>
                            </a>
                            <i class="bx bxs-chevron-down arrow"></i>
                        </div>
                        <ul class="sub-menu">
                            @canany(['table-list', 'table-create', 'table-edit', 'table-delete'])
                                <li><a href="{{ route('table-list') }}">Manage Table</a></li>
                            @endcanany
                            {{-- <li><a href="management_reservation.html" aria-hidden="true">Manage reservations</a></li> --}}
                        </ul>
                    </li>
                @endcanany

                <li>
                    <div class="iocn-link">
                        <a href="javascript:void(0)" aria-hidden="true">
                            <i class="bx bxs-file-doc"></i>
                            <span class="link_name">Tax Management System</span>
                        </a>
                        <i class="bx bxs-chevron-down arrow"></i>
                    </div>
                    <ul class="sub-menu">
                        <li><a class="link_name" href="javascript:void(0)">Tax Management System</a></li>
                        {{-- @if (Gate::check('tax-class-list') || Gate::check('tax-class-create') || Gate::check('tax-class-edit') || Gate::check('tax-class-delete'))
                            <li><a href="{{ route('tax.index') }}" aria-hidden="true">Tax Session/Class</a></li>
                        @endif
                        @if (Gate::check('tax-list') || Gate::check('tax-create') || Gate::check('tax-edit') || Gate::check('tax-delete'))
                            <li><a href="{{ route('percent.index') }}" aria-hidden="true">Tax Management</a></li>
                        @endif --}}

                        @if (Gate::check('tax-list') || Gate::check('tax-create') || Gate::check('tax-edit') || Gate::check('tax-delete'))
                            <li><a href="{{ route('taxManage.index') }}" aria-hidden="true">Tax</a></li>
                        @endif

                    </ul>
                </li>
                <li id="menu-management-menu">
                    <div class="iocn-link">
                        <a href="#" aria-hidden="true">
                            <i class="bx bx-store-alt"></i>
                            <span class="link_name">Menu Management</span>
                        </a>
                        <i class="bx bxs-chevron-down arrow"></i>
                    </div>
                    <ul class="sub-menu">
                        @canany(['item_tags', 'item_tags-create', 'item_tag-edit', 'item_tags.destroy'])
                            <li><a href="{{ route('item_tags') }}" aria-hidden="true">Item Types</a></li>
                        @endcanany
                        @canany(['category-list', 'category-create', 'category-edit', 'category-delete'])
                            <li><a href="{{ route('menu-type-list') }}" aria-hidden="true">Menu Types</a></li>
                        @endcanany
                        @canany(['category-list', 'category-create', 'category-edit', 'category-delete'])
                            <li><a href="{{ route('category-list') }}" aria-hidden="true">Categories</a></li>
                        @endcanany
                        @canany(['item-list', 'item-create', 'item-edit', 'item-delete'])
                            <li><a href="{{ route('item-list') }}" aria-hidden="true">Items</a></li>
                        @endcanany
                        <!-- @canany(['modifier-category-list', 'modifier-category-create', 'modifier-category-edit',
                            'modifier-category-delete'])
                            <li><a href="{{ route('modifier-category-list') }}" aria-hidden="true">Modifiers Category</a></li>
                        @endcanany -->
                        @canany(['modifier-list', 'modifier-create', 'modifier-edit', 'modifier-delete'])
                            <li><a href="{{ route('modifier-group-list') }}" aria-hidden="true">Modifiers Group</a></li>
                            <li><a href="{{ route('modifier-list') }}" aria-hidden="true">Modifier</a></li>
                        @endcanany
                        @canany(['price-editor.index', 'price-editor.update', 'price-editor.bulk-update'])
                            <li><a href="{{ route('price-editor.index') }}" aria-hidden="true">Edit Price</a></li>
                        @endcanany
                        @canany(['variant-list', 'variant-create', 'variant-edit', 'variant-delete'])
                            <!-- <li><a href="{{ route('variant-list') }}" aria-hidden="true">Variants</a></li> -->
                            <!-- <li><a href="{{ route('variant-group-list') }}" aria-hidden="true">Variant Group</a></li> -->
                        @endcanany
                        @canany(['item-list', 'item-create', 'item-edit', 'item-delete'])
                            {{-- <li><a href="{{ route('item-list') }}" aria-hidden="true">Items</a></li> --}}
                        @endcanany
                        {{-- <li><a href="stock_management.html" aria-hidden="true">Stock Management</a></li> --}}
                        {{-- <li><a href="waste_management.html" aria-hidden="true">Waste Management</a></li> --}}

                        @canany(['ingredient-list', 'ingredient-create', 'ingredient-edit', 'ingredient-delete'])
                            <li><a href="{{ route('ingredient-list') }}" aria-hidden="true">Ingredient</a></li>
                        @endcanany

                        @canany(['category-schedule-list', 'category-schedule-create', 'category-schedule-edit', 'category-schedule-delete'])
                            <li><a href="{{ route('category-schedule-list') }}" aria-hidden="true">Category Schedules</a></li>
                        @endcanany

                        @canany(['happy_hours.show', 'happy_hours.create', 'happy_hours.edit', 'happy_hours.delete'])
                            <li><a href="{{ route('happy-hours-list') }}" aria-hidden="true">Happy Hours</a></li>
                        @endcanany
                    </ul>
                </li>
                <li>
                    <div class="iocn-link">
                        <a href="#" aria-hidden="true">
                            <i class="bx bx-coin-stack"></i>
                            <span class="link_name">Inventory Management</span>
                        </a>
                        <i class="bx bxs-chevron-down arrow"></i>
                    </div>
                    <ul class="sub-menu">
                        @canany(['inventory-list', 'inventory-create', 'inventory-delete', 'inventory-transactions'])
                            <li><a href="{{ route('inventory-list') }}" aria-hidden="true">Items</a></li>
                        @endcanany
                        @canany(['ingredient-inventory-list', 'ingredient-inventory-create', 'ingredient-inventory-delete', 'ingredient-inventory-transactions'])
                            <li><a href="{{ route('ingredient-inventory-list') }}" aria-hidden="true">Ingredients</a></li>
                        @endcanany

                        @canany(['vendor-list', 'vendor-create', 'vendor-delete', 'vendor-edit'])
                        <li><a href="{{ route('vendor-list') }}" aria-hidden="true">Vendors</a></li>
                        @endcanany

                        @canany(['vendor-invoice-read', 'vendor-invoice-create', 'vendor-invoice-delete', 'vendor-invoice-edit'])
                        <li><a href="{{ route('vendor-invoice-list') }}" aria-hidden="true">Vendor Invoices</a></li>
                        @endcanany
                        {{-- <li><a href="stock_management.html" aria-hidden="true">Stock Management</a></li> --}}
                        {{-- <li><a href="waste_management.html" aria-hidden="true">Waste Management</a></li> --}}
                    </ul>
                </li>

                <li>
                    <div class="iocn-link">
                        <a href="javascript:void(0)" aria-hidden="true">
                            <i class="bx bx-wallet"></i>
                            <span class="link_name">Expense Management</span>
                        </a>
                        <i class="bx bxs-chevron-down arrow"></i>
                    </div>
                    <ul class="sub-menu">
                        <li><a class="link_name" href="javascript:void(0)">Expense Management</a></li>
                        @if (
                Gate::check('expense-type-list') ||
                Gate::check('expense-type-create') ||
                Gate::check('expense-type-edit') ||
                Gate::check('expense-type-delete')
            )
                            <li><a href="{{ route('expense-type-list') }}" aria-hidden="true">Expense Type</a></li>
                        @endif
                        @if (
                Gate::check('expense-list') ||
                Gate::check('expense-create') ||
                Gate::check('expense-edit') ||
                Gate::check('expense-delete')
            )
                            <li><a href="{{ route('expense-list') }}" aria-hidden="true">Expense</a></li>
                        @endif
                    </ul>
                </li>

                <li>
                    <div class="iocn-link">
                        <a href="#" aria-hidden="true">
                            <i class="bx bx-cart"></i>
                            <span class="link_name">Order Management</span>
                        </a>
                        <i class="bx bxs-chevron-down arrow"></i>
                    </div>
                    <ul class="sub-menu">
                        @can('restaurant.orders')
                            <li><a href="{{ route('order-list') }}">Tickets History</a></li>
                        @endcanany

                        @can('restaurant.payments')
                            <li><a href="{{ route('restaurant.payments') }}">Payment History</a></li>
                        @endcan
                    </ul>
                </li>

                <li>
                    <div class="iocn-link">
                        <a href="#" aria-hidden="true">
                            <i class="bx bx-user"></i>
                            <span class="link_name "> Employees</span>
                        </a>
                        <i class="bx bxs-chevron-down arrow"></i>
                    </div>
                    <ul class="sub-menu">
                        @canany(['user-list', 'user-create', 'user-edit', 'user-delete'])
                            <li><a href="{{ route('user-list') }}">Employees</a></li>
                        @endcanany
                        @canany(['shifts.index', 'shifts.store', 'shifts.update', 'shifts.destroy'])
                            <li><a href="{{ route('shifts.index') }}">Shifts</a></li>
                        @endcanany
                        @can('cash_transactions.index')
                            <li><a href="{{ route('cash_transactions.index') }}">Cash In/Out</a></li>
                        @endcan
                    </ul>
                </li>
                <li>
                    <div class="iocn-link">
                        <a href="#" aria-hidden="true">
                            <i class="bx bx-lock"></i>
                            <span class="link_name "> Roles & Permissions</span>
                        </a>
                        <i class="bx bxs-chevron-down arrow"></i>
                    </div>
                    <ul class="sub-menu">
                        @canany(['role-list', 'role-create', 'role-edit', 'role-delete'])
                        <li><a href="{{ route('role-list') }}">Roles</a></li>
                        @endcanany
                        @canany(['role-list', 'role-create', 'role-edit', 'role-delete', 'role-permission', 'role-permission-store'])
                        <li><a href="{{ route('role-permission') }}"> Role Permissions</a></li>
                        @endcanany
                    </ul>
                </li>
                @canany(['reservations.list', 'reservations.create', 'reservations.edit', 'reservations.cancel', 'reservations.payments', 'reservations.settings'])
                <li>
                    <a href="{{ route('reservation.index') }}" aria-hidden="true">
                        <i class=" bx bx-book-content"></i>
                        <span class="link_name">Reservation</span>
                    </a>
                    <ul class="sub-menu blank">
                        <li><a class="link_name" href="{{ route('reservation.index') }}">Reservation</a></li>
                    </ul>
                </li>
                @endcanany

                <li class="popupMenu">
                    <div class="iocn-link">
                        <a href="javascript:void(0)" aria-hidden="true">
                            <i class="bx bxs-report"></i>
                            <span class="link_name"> Reports</span>
                        </a>
                        <i class="bx bxs-chevron-right arrow"></i>
                    </div>
                    <ul class="sub-menu report-menu">
                        <li><a class="link_name" href="javascript:void(0)">Reports</a></li>

                        <li>
                            <a class="nav-link" href="javascript:void(0)">
                                Sales Reports
                            </a>
                            <ul class="sub-menu dropdown-menu report-menu">
                                <li><a href="{{ route('report.sales-summary-report') }}" aria-hidden="true">Sales Summary Report</a></li>
                                <li><a href="{{ route('daily-sales-report') }}" aria-hidden="true">Daily sales report</a></li>
                                <li><a href="{{ route('hourly-sales-report') }}" aria-hidden="true">Hourly sales report</a></li>
                                <li><a href="{{ route('report.tip-report') }}" aria-hidden="true">Tip Report</a></li>
                                <li><a href="{{ route('report.sales-by-item-report') }}" aria-hidden="true">Sales by Item</a></li>
                                <li><a href="{{ route('sales-by-item-type-report') }}" aria-hidden="true">Sales by Item Type</a></li>
                                <li><a href="{{ route('sales-by-category-report') }}" aria-hidden="true">Sales by Category</a></li>
                                <li><a href="{{ route('report.sales-by-shift-report') }}" aria-hidden="true">Sales by Shift</a></li>
                                <li><a href="{{ route('sales-by-order-type-report') }}" aria-hidden="true">Sales by Order Type</a></li>
                                <li><a href="{{ route('order-summary-report') }}" aria-hidden="true">Order Summary</a></li>
                                <li><a href="{{ route('report.delivery-partner-report') }}" aria-hidden="true">Delivery Partner Report</a></li>
                            </ul>
                        </li>

                        <li>
                            <a class="nav-link" href="javascript:void(0)">
                                Employee Reports
                            </a>
                            <ul class="sub-menu dropdown-menu report-menu">
                                <li><a href="{{ route('clock-in-out-report') }}" aria-hidden="true">Attendance & Shift Report</a></li>
                                <li><a href="{{ route('report.sales-by-employee-report') }}" aria-hidden="true">Sales by Employee</a></li>
                                <li><a href="{{ route('report.employee-salaries-report') }}" aria-hidden="true">Employee Salaries</a></li>
                            </ul>
                        </li>

                        <li>
                            <a class="nav-link" href="javascript:void(0)">
                                Gift Card Reports
                            </a>
                            <ul class="sub-menu dropdown-menu report-menu">
                                <li><a href="{{ route('report.gift-card-issued') }}" aria-hidden="true">Gift Card Issues</a></li>
                                <li><a href="{{ route('report.gift-cards-transaction') }}" aria-hidden="true">Gift Card Transaction</a></li>
                                <li><a href="{{ route('report.gift-card-usage') }}" aria-hidden="true">Gift Card Usage</a></li>
                            </ul>
                        </li>

                        <li>
                            <a class="nav-link" href="javascript:void(0)">
                                Payment Reports
                            </a>
                            <ul class="sub-menu dropdown-menu report-menu">
                                <li><a href="{{ route('payment-report') }}" aria-hidden="true">Payment Report</a></li>
                                <li><a href="{{ route('reports.batch') }}" aria-hidden="true">Batch Report</a></li>
                                <li><a href="{{ route('reports.batch.summary') }}" aria-hidden="true">Batch Summary Report</a></li>
                                {{-- <li><a href="daily_discount_report.html" aria-hidden="true">Daily discount report</a></li>
                                <li><a href="daily_wastage_report.html" aria-hidden="true">Daily wastage Report</a></li>
                                <li><a href="daily_expense_report.html" aria-hidden="true">Daily Expense Report</a></li> 
                                <li><a href="daily_profit_report.html" aria-hidden="true"> Daily profit Report</a></li>
                                <li><a href="daily_labour_breakdown.html" aria-hidden="true">Daily labour Breakdown</a></li> --}}
                            </ul>
                        </li>

                        <li>
                            <a class="nav-link" href="javascript:void(0)">
                                Audit Reports
                            </a>
                            <ul class="sub-menu dropdown-menu report-menu">
                                <li><a href="{{ route('report.shift-summary-report') }}" aria-hidden="true">Shift Summary Report</a></li>
                                <li><a href="{{ route('report.shift-report') }}" aria-hidden="true">Drawer Report</a></li>
                                <li><a href="{{ route('cloasing-report') }}" aria-hidden="true">Closing report</a></li>
                                <li><a href="{{ route('cash-in-out-report') }}" aria-hidden="true">Cash In/Out Report</a></li>
                                <li><a href="{{ route('report.pos-devices-report') }}" aria-hidden="true">Devices Report</a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="nav-link" href="javascript:void(0)">
                                Customer Reports
                            </a>
                            <ul class="sub-menu dropdown-menu report-menu">
                                <li><a href="{{ route('report.customer-purchase-history') }}" aria-hidden="true">Customer Purchase History</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li>
                    <div class="iocn-link">
                        <a href="#" aria-hidden="true">
                            <i class="bx bx-dock-left"></i>
                            <span class="link_name"> CRM</span>
                        </a>
                        <i class="bx bxs-chevron-down arrow"></i>
                    </div>
                    <ul class="sub-menu">
                        <li><a class="link_name" href="#">CRM</a></li>
                        <li><a href="{{ route('customer-reviews.index') }}" aria-hidden="true">Customer Feedback</a></li>
                        {{-- <li><a href="loyality_program.html" aria-hidden="true">Loyalty Program</a></li> --}}
                    </ul>
                </li>

                <li>
                    <div class="iocn-link">
                        <a href="#" aria-hidden="true">
                            <i class="bx bx-book-alt"></i>
                            <span class="link_name"> Devices Management</span>
                        </a>
                        <i class="bx bxs-chevron-down arrow"></i>
                    </div>
                    <ul class="sub-menu">
                        <li><a class="link_name" href="#">Devices</a></li>
                        @canany(['pos-devices.index', 'pos-devices.store', 'pos-devices.update', 'pos-devices.delete', 'pos-devices.toggleStatus'])
                            <li><a href="{{ route('pos-devices.index') }}" aria-hidden="true"> Devices</a></li>
                        @endcanany
                        @canany(['restaurant.terminal-settings.index', 'restaurant.terminal-settings.store', 'restaurant.terminal-settings.update', 'restaurant.terminal-settings.delete'])
                            <li><a href="{{ route('restaurant.terminal-settings.index') }}" aria-hidden="true">Terminals</a></li>
                        @endcanany
                    </ul>
                </li>

                <li>
                    <div class="iocn-link">
                        <a href="#" aria-hidden="true">
                            <i class="bx bxs-offer"></i>
                            <span class="link_name">Offer Management</span>
                        </a>
                        <i class="bx bxs-chevron-down arrow"></i>
                    </div>
                    <ul class="sub-menu">
                        <li><a class="link_name" href="#">Offer Management</a></li>
                        <!-- <li><a href="{{ route('gift-cards.index') }}" aria-hidden="true"> Gift Cards</a></li> -->
                        @canany(['gift-cards.index', 'gift-cards.update-balance', 'gift-cards.transactions'])
                            <li><a href="{{ route('gift-cards.index') }}" aria-hidden="true">Gift Cards</a></li>
                        @endcanany
                        @can('gift-vouchers.index')
                        <li><a href="{{ route('gift-vouchers.index') }}" aria-hidden="true"> Gift Voucher</a></li>
                        @endcan
                        {{-- <li><a href="#" aria-hidden="true">Offer management</a></li>
                        <li><a href="#" aria-hidden="true">Coupon Management</a></li> --}}
                    </ul>
                </li>

                <li>
                    <div class="iocn-link">
                        <a href="#" aria-hidden="true">
                            <i class="bx bxs-offer"></i>
                            <span class="link_name">Website</span>
                        </a>
                        <i class="bx bxs-chevron-down arrow"></i>
                    </div>
                    <ul class="sub-menu">
                        <li><a href="{{route('website.settings')}}" aria-hidden="true">Global Setting</a></li>
                        <li><a href="{{route('website.gallery')}}" aria-hidden="true">Gallery</a></li>
                        <li><a href="{{route('website.menu.settings')}}" aria-hidden="true">Menu Items</a></li>
                    </ul>
                </li>

                {{-- <li>
                    @canany(['reservation-list', 'reservation-create', 'reservation-edit', 'role-delete'])
                        <div class="iocn-link">
                            <a href="" aria-hidden="true">
                                <i class="bx bx-cog"></i>
                                <span class="link_name"> Reservation </span>
                            </a>
                            <i class="bx bxs-chevron-down arrow"></i>
                        </div>
                    @endcanany
                    <ul class="sub-menu">
                        <li><a class="link_name" href="javascript:void(0)"> Reservation </a></li>
                        <li><a href="setting.html" aria-hidden="true"> Booking Schedule </a></li>
                        <li><a href="setting.html" aria-hidden="true"> Notification </a></li>
                        <li><a href="setting.html" aria-hidden="true"> Payments </a></li>
                        <li><a href="setting.html" aria-hidden="true"> Booking Reservation </a></li>
                        <li><a href="setting.html" aria-hidden="true"> View Bookings </a></li>
                        <li><a href="setting.html" aria-hidden="true"> Restaurant Bookings </a></li>
                    </ul>
                </li> --}}

                <li>
                    <div class="iocn-link">
                        <a href="#" aria-hidden="true">
                            <i class="bx bx-cog"></i>
                            <span class="link_name "> Settings</span>
                        </a>
                        <i class="bx bxs-chevron-down arrow"></i>
                    </div>
                    <ul class="sub-menu">
                        @if (Gate::check('settings.index') || Gate::check('settings.update'))
                            <li><a href="{{ route('settings.index') }}" aria-hidden="true">General Settings</a></li>
                        @endif
                        @canany(['restaurant.addressSettings'])
                        <li><a href="{{ route('restaurant.addressSettings') }}">Addresses</a></li>
                        @endcanany
                        @canany(['manage-payment-methods', 'manage-payment-method-edit'])
                            <li><a href="{{ route('manage-payment-methods') }}">Payment Methods</a></li>
                        @endcanany
                        @if (Gate::check('tip-list') || Gate::check('tip-create') || Gate::check('tip-edit') || Gate::check('tip-delete'))
                            <li><a href="{{ route('tipPercentage.index') }}" aria-hidden="true">Tip Suggestion</a></li>
                        @endif

                        @canany(['report-notification.index', 'report-notification.store', 'report-notification.destroy'])
                        <li><a href="{{ route('settings.report-notifications') }}">Report Notification Emails</a></li>
                        @endcanany

                        @canany(['tip-settings.show', 'tip-settings.save'])
                            <li><a href="{{ route('settings.tip-settings') }}">Tip Settings</a></li>
                        @endcanany

                        @if(auth()->user()->hasRole('RestaurantSuperAdmin'))
                            <li><a href="{{ route('settings.maintenance') }}">Maintenance</a></li>
                        @endif
                    </ul>
                </li>

                <li>
                    <div class="iocn-link">
                        <a href="{{ route('delivery-partners.index') }}" aria-hidden="true">
                            <i class="bx bx-bolt-circle"></i>
                            <span class="link_name "> Delivery Partners</span>
                        </a>
                    </div>
                </li>
                <li>
                    <a href="{{ route('sync.dashboard') }}" aria-hidden="true">
                        <i class="bx bx-layout"></i>
                        <span class="link_name">Sync to cloud</span>
                    </a>
                </li>
        @endif
        </ul>
    </div>


<style>
    .sidebar li .report-menu {
        display: none;
        position: absolute;
        left: 100%;
        top: -7px;
        min-width: 240px;
    }

    .sidebar li:hover>.report-menu {
        display: block;
    }

    .sidebar .nav-links .report-menu
    {
        padding-left: 0px !important;
    }

    .sidebar .nav-links .report-menu li {
        padding-left: 20px !important;
    }

    .report-menu {
        background-color: #0980B2 !important;
    }

    .report-menu a.active {
        border: 0;
    }

</style>