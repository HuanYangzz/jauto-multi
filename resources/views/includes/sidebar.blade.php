<aside class="left-sidebar" data-sidebarbg="skin6">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar" data-sidebarbg="skin6">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="nav-small-cap clickable-row" onclick="toggle_menu('home')"><span class="hide-menu">MARCUS</span></li>
                <li class="sidebar-item home" data-id="home"> <a class="sidebar-link sidebar-link" href="{{url('/')}}"
                    aria-expanded="false"><i data-feather="home" class="feather-icon"></i><span
                class="hide-menu">Dashboard</span></a></li>
                <li class="list-divider"></li>

                @can("PRESALES")
                <li class="nav-small-cap clickable-row" onclick="toggle_menu('presales')"><span class="hide-menu">Pre Sales</span></li>
                <li class="sidebar-item presales" data-id="presales"> <a class="sidebar-link sidebar-link" href="{{url('/presales/prospect')}}"
                    aria-expanded="false"><i data-feather="plus" class="feather-icon"></i><span
                class="hide-menu">Prospect New</span></a></li>
                <li class="sidebar-item presales" data-id="presales"> <a class="sidebar-link sidebar-link" href="{{url('/presales/prospect_book')}}"
                    aria-expanded="false"><i data-feather="book-open" class="feather-icon"></i><span
                class="hide-menu">Prospect Contact Book</span></a></li>
                <li class="sidebar-item presales" data-id="presales"> <a class="sidebar-link sidebar-link" href="{{url('/presales/events')}}"
                    aria-expanded="false"><i data-feather="calendar" class="feather-icon"></i><span
                class="hide-menu">Event Management</span></a></li>
                <li class="list-divider"></li>
                @else
                <li class="nav-small-cap"><span class="hide-menu">Pre Sales</span></li>
                <li class="list-divider"></li>
                @endcan

                @can("SALES")
                <li class="nav-small-cap clickable-row" onclick="toggle_menu('sales')"><span class="hide-menu">Sales</span></li>
                <li class="sidebar-item sales" data-id="sales"> <a class="sidebar-link sidebar-link" href="{{url('/sales/customer_book')}}"
                    aria-expanded="false"><i data-feather="book-open" class="feather-icon"></i><span
                class="hide-menu">Customer Contact Book</span></a></li>
                <li class="sidebar-item sales" data-id="sales"> <a class="sidebar-link sidebar-link" href="{{url('/sales/vso_list')}}"
                    aria-expanded="false"><i data-feather="menu" class="feather-icon"></i><span
                class="hide-menu">VSO List</span></a></li>
                <li class="sidebar-item sales" data-id="sales"> <a class="sidebar-link sidebar-link" href="{{url('/sales/vso/NEW')}}"
                    aria-expanded="false"><i data-feather="plus" class="feather-icon"></i><span
                class="hide-menu">VSO New</span></a></li>
                <li class="sidebar-item sales" data-id="sales"> <a class="sidebar-link sidebar-link" href="{{url('/sales/broker/NEW')}}"
                    aria-expanded="false"><i data-feather="plus-circle" class="feather-icon"></i><span
                class="hide-menu">VSO Broker New</span></a></li>
                <li class="sidebar-item sales" data-id="sales"> <a class="sidebar-link sidebar-link" href="{{url('/sales/payment')}}"
                    aria-expanded="false"><i data-feather="dollar-sign" class="feather-icon"></i><span
                class="hide-menu">VSO Payment</span></a></li>
                <li class="sidebar-item sales" data-id="sales"> <a class="sidebar-link sidebar-link" href="{{url('/sales/trade_in')}}"
                    aria-expanded="false"><i data-feather="corner-right-down" class="feather-icon"></i><span
                class="hide-menu">Vehicle Trade In</span></a></li>
                <li class="sidebar-item sales" data-id="sales"> <a class="sidebar-link sidebar-link" href="{{url('/sales/salesmen_commission')}}"
                    aria-expanded="false"><i data-feather="percent" class="feather-icon"></i><span
                class="hide-menu">Commission Salesman</span></a></li>
                <li class="sidebar-item sales" data-id="sales"> <a class="sidebar-link sidebar-link" href="{{url('/sales/commission_payment')}}"
                    aria-expanded="false"><i data-feather="user-check" class="feather-icon"></i><span
                class="hide-menu">Commission Payment</span></a></li>
                <li class="list-divider"></li>
                @else
                <li class="nav-small-cap"><span class="hide-menu">Sales</span></li>
                <li class="list-divider"></li>
                @endcan

                @can("PROCUREMENT")
                <li class="nav-small-cap"><span class="hide-menu">Supplier</span></li>
                <li class="list-divider"></li>
                @else
                <li class="nav-small-cap"><span class="hide-menu">Supplier</span></li>
                <li class="list-divider"></li>
                @endcan

                @can("INVENTORY")
                <li class="nav-small-cap clickable-row" onclick="toggle_menu('inventory')"><span class="hide-menu">Stock</span></li>
                <li class="sidebar-item inventory" data-id="inventory"> <a class="sidebar-link sidebar-link" href="{{url('/inventory/vehicle_stock')}}"
                    aria-expanded="false"><i class="fas fa-car side-icon"></i><span
                class="hide-menu"> Vehicle Listing</span></a></li>
                <li class="sidebar-item inventory" data-id="inventory"> <a class="sidebar-link sidebar-link" href="{{url('/inventory/vehicle_list')}}"
                    aria-expanded="false"><i class="fas fa-car side-icon"></i><span
                class="hide-menu"> Vehicle Management</span></a></li>
                <li class="sidebar-item inventory" data-id="inventory"> <a class="sidebar-link sidebar-link" href="{{url('/inventory/stock_list')}}"
                    aria-expanded="false"><i class="fas fa-cogs side-icon"></i><span
                class="hide-menu"> Stock Management</span></a></li>
                <li class="sidebar-item inventory" data-id="inventory"> <a class="sidebar-link sidebar-link" href="{{url('/inventory/non_stock_list')}}"
                    aria-expanded="false"><i class="fas fa-gift side-icon"></i><span
                class="hide-menu"> Non-Stock Management</span></a></li>
                <li class="sidebar-item inventory" data-id="inventory"> <a class="sidebar-link sidebar-link" href="{{url('/inventory/fee_list')}}"
                    aria-expanded="false"><i class="fas fa-receipt side-icon"></i><span
                class="hide-menu"> Fee Management</span></a></li>
                <li class="sidebar-item inventory" data-id="inventory"> <a class="sidebar-link sidebar-link" href="{{url('/inventory/stock_distribution')}}"
                    aria-expanded="false"><i class="fas fa-exchange-alt side-icon"></i><span
                class="hide-menu"> Stock Transfer</span></a></li>
                <li class="list-divider"></li>
                @else
                <li class="nav-small-cap"><span class="hide-menu">Stock</span></li>
                <li class="list-divider"></li>
                @endcan

                @can("PRINCIPAL")
                <li class="nav-small-cap"><span class="hide-menu">Principal</span></li>
                <li class="list-divider"></li>
                @else
                <li class="nav-small-cap"><span class="hide-menu">Principal</span></li>
                <li class="list-divider"></li>
                @endcan

                @can("SMART ANALYSIS")
                <li class="nav-small-cap"><span class="hide-menu">Smart Analysis</span></li>
                <li class="list-divider"></li>
                @else
                <li class="nav-small-cap"><span class="hide-menu">Smart Analysis</span></li>
                <li class="list-divider"></li>
                @endcan

                @can("COMPANY")
                <li class="nav-small-cap clickable-row" onclick="toggle_menu('company')"><span class="hide-menu">Company</span></li>
                <li class="sidebar-item company" data-id="company"> <a class="sidebar-link sidebar-link" href="{{url('/company/profile')}}"
                    aria-expanded="false"><i class="fa fa-university sidebar-icon"></i><span
                class="hide-menu"> Company Profile</span></a></li>
                <li class="sidebar-item company" data-id="company"> <a class="sidebar-link sidebar-link" href="{{url('/company/branches')}}"
                    aria-expanded="false"><i class="fas fa-sitemap sidebar-icon"></i><span
                class="hide-menu"> Branch Management</span></a></li>
                <li class="sidebar-item company" data-id="company"> <a class="sidebar-link sidebar-link" href="{{url('/company/salesmen')}}"
                    aria-expanded="false"><i class="fas fa-user-tie sidebar-icon"></i><span
                class="hide-menu"> Salesman Management</span></a></li>
                <li class="sidebar-item company" data-id="company"> <a class="sidebar-link sidebar-link" href="{{url('/company/employees')}}"
                    aria-expanded="false"><i class="fas fa-user-edit sidebar-icon"></i><span
                class="hide-menu"> Employee Management</span></a></li>
                <li class="sidebar-item company" data-id="company"> <a class="sidebar-link sidebar-link" href="{{url('/company/brokers')}}"
                    aria-expanded="false"><i class="fas fa-user-secret sidebar-icon"></i><span
                class="hide-menu"> Broker Management</span></a></li>
                <li class="sidebar-item company" data-id="company"> <a class="sidebar-link sidebar-link" href="{{url('/company/users')}}"
                    aria-expanded="false"><i class="fas fa-user sidebar-icon"></i><span
                class="hide-menu"> User Management</span></a></li>
                <li class="list-divider"></li>
                @else
                <li class="nav-small-cap"><span class="hide-menu">Company</span></li>
                <li class="list-divider"></li>
                @endcan

                @hasrole("System Admin")
                <li class="nav-small-cap"><span class="hide-menu">System Admin</span></li>
                <li class="list-divider"></li>
                @endhasrole
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
