@extends('layouts.app')

@section('content-page')
    <style>
        .note-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.2);
            padding: 12px 16px;
            width: 500px;
            transition: all 0.3s ease-in-out;
        }
        .note-card input, .note-card textarea {
            width: 100%;
            border: none;
            outline: none;
            font-size: 16px;
            resize: none;
            background: transparent;
            margin-bottom: 8px;
        }
        .note-card.collapsed .note-title, .note-card.collapsed .note-actions {
            display: none;
        }
        .note-actions {
            display: flex;
            justify-content: flex-end;
        }
        .note-actions button {
            background: #f1f3f4;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
        }
        .note-actions button:hover {
            background: #e0e0e0;
        }
        .note-description {
            transition: all 0.3s ease;
            padding-top: 5px;
        }
        .date-filter-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .stat-card {
            cursor: pointer;
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .module-link {
            text-decoration: none;
            color: inherit;
        }
        .badge-custom {
            font-size: 12px;
            padding: 4px 8px;
        }
    </style>

    <div class="container">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Dashboard</h3>
                </div>
                <div class="ms-md-auto py-2 py-md-0">
                    <a href="{{ route('admin.customer.create') }}" class="btn btn-primary btn-round">Add Customer</a>
                </div>
            </div>

            <!-- Date Filter Card -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card date-filter-card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <label class="fw-bold">Select Date Range</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="date" id="date_from" class="form-control" value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="col-md-3">
                                    <input type="date" id="date_to" class="form-control" value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="col-md-3">
                                    <div class="btn-group w-100">
                                        <button onclick="filterDashboard('today')" class="btn btn-light btn-sm">Today</button>
                                        <button onclick="filterDashboard('yesterday')" class="btn btn-light btn-sm">Yesterday</button>
                                        <button onclick="filterDashboard('week')" class="btn btn-light btn-sm">This Week</button>
                                        <button onclick="filterDashboard('month')" class="btn btn-light btn-sm">This Month</button>
                                        <button onclick="applyDateFilter()" class="btn btn-warning btn-sm">Apply</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Row 1 -->
            <div class="row">
                <div class="col-sm-6 col-md-3">
                    <a href="{{ route('admin.dealer.index') }}" class="module-link">
                        <div class="card card-stats card-round stat-card">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-primary bubble-shadow-small">
                                            <i class="fas fa-truck"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-3 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Total Dealers</p>
                                            <h4 class="card-title" id="dealerCount">0</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-md-3">
                    <a href="{{ route('admin.product.index') }}" class="module-link">
                        <div class="card card-stats card-round stat-card">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-success bubble-shadow-small">
                                            <i class="fas fa-boxes"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-3 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Total Products</p>
                                            <h4 id="productCount">0</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-md-3">
                    <a href="{{ route('admin.customer.index') }}" class="module-link">
                        <div class="card card-stats card-round stat-card">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                            <i class="fas fa-users"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-3 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Total Customers</p>
                                            <h4 id="customerCount">0</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-md-3">
                    <a href="{{ route('admin.stock.index') }}" class="module-link">
                        <div class="card card-stats card-round stat-card">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-info bubble-shadow-small">
                                            <i class="fas fa-database"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-3 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Total Stock Items</p>
                                            <h4 id="totalStock">0</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Stats Row 2 - Sales & Transactions -->
            <div class="row">
                <div class="col-sm-6 col-md-3">
                    <a href="{{ route('admin.sale.index') }}" class="module-link">
                        <div class="card card-stats card-round stat-card">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-primary bubble-shadow-small">
                                            <i class="fas fa-shopping-cart"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-3 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Today's Sales</p>
                                            <h4 id="sales">₹0</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-md-3">
                    <a href="{{ route('admin.transaction.index') }}" class="module-link">
                        <div class="card card-stats card-round stat-card">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-success bubble-shadow-small">
                                            <i class="fas fa-arrow-down"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-3 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Transaction IN</p>
                                            <h4 id="transactionin">₹0</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-md-3">
                    <a href="{{ route('admin.transaction.index') }}" class="module-link">
                        <div class="card card-stats card-round stat-card">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-danger bubble-shadow-small">
                                            <i class="fas fa-arrow-up"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-3 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Transaction OUT</p>
                                            <h4 id="transactionout">₹0</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-md-3">
                    <a href="{{ route('admin.purchase.index') }}" class="module-link">
                        <div class="card card-stats card-round stat-card">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-warning bubble-shadow-small">
                                            <i class="fas fa-chart-line"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-3 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Total Purchases</p>
                                            <h4 id="totalPurchases">₹0</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Stats Row 3 - Payment Methods -->
            <div class="row">
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-primary bubble-shadow-small">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Sales Cash</p>
                                        <h4 id="salesAmountCash">₹0</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-success bubble-shadow-small">
                                        <i class="fas fa-mobile-alt"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Sales Online</p>
                                        <h4 id="salesAmountOnline">₹0</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-info bubble-shadow-small">
                                        <i class="fas fa-hand-holding-usd"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Deductions Cash</p>
                                        <h4 id="deductionAmountCash">₹0</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-warning bubble-shadow-small">
                                        <i class="fas fa-laptop"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Deductions Online</p>
                                        <h4 id="deductionAmountOnline">₹0</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction Tables -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <strong><h4 style="text-align: center;margin-top: .5rem;">Transactions (IN)</h4></strong>
                        <div class="card-body" style="height: 20em; overflow-y: scroll;">
                            <table class="table table-striped">
                                <thead>
                                    <tr><th>Sr no</th><th>Amount</th><th>Payment Mode</th><th>Remark</th><th>Action</th></tr>
                                </thead>
                                <tbody id="transactionListIn"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <strong><h4 style="text-align: center;margin-top: .5rem;">Transactions (OUT)</h4></strong>
                        <div class="card-body" style="height: 20em; overflow-y: scroll;">
                            <table class="table table-striped">
                                <thead>
                                    <tr><th>Sr no</th><th>Amount</th><th>Payment Mode</th><th>Remark</th><th>Action</th></tr>
                                </thead>
                                <tbody id="transactionListOut"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <h4 style="text-align: center; margin-top: .5rem;">Sales Transactions</h4>
                        <div class="card-body" style="height: 20em; overflow-y: scroll;">
                            <table class="table table-striped">
                                <thead>
                                    <tr><th>Sr no</th><th>Amount</th><th>Customer</th><th>Payment Mode</th><th>Action</th></tr>
                                </thead>
                                <tbody id="salesTransactionList"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <h4 style="text-align: center; margin-top: .5rem;">EMI Transactions</h4>
                        <div class="card-body" style="height: 20em; overflow-y: scroll;">
                            <table class="table table-striped">
                                <thead>
                                    <tr><th>Sr no</th><th>Amount</th><th>Customer</th><th>Payment Mode</th><th>Action</th></tr>
                                </thead>
                                <tbody id="emiTransactionList"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   <script>
    let currentDateFrom = '{{ date('Y-m-d') }}';
    let currentDateTo = '{{ date('Y-m-d') }}';

    function filterDashboard(type) {
        const today = new Date();
        let from = new Date();
        let to = new Date();

        switch(type) {
            case 'today':
                from = today;
                to = today;
                break;
            case 'yesterday':
                from.setDate(today.getDate() - 1);
                to = from;
                break;
            case 'week':
                from.setDate(today.getDate() - 7);
                to = today;
                break;
            case 'month':
                from.setDate(1);
                to = today;
                break;
        }

        $('#date_from').val(formatDate(from));
        $('#date_to').val(formatDate(to));
        applyDateFilter();
    }

    function formatDate(date) {
        return date.toISOString().split('T')[0];
    }

    function applyDateFilter() {
        currentDateFrom = $('#date_from').val();
        currentDateTo = $('#date_to').val();
        
        fetchDashboardData();
        fetchAllTransactions();
    }

    function fetchDashboardData() {
        $.ajax({
            url: "{{ route('dashboard.data') }}",
            method: "GET",
            data: {
                date_from: currentDateFrom,
                date_to: currentDateTo
            },
            success: function(response) {
                $('#dealerCount').text(response.dealer_count);
                $('#productCount').text(response.product_count);
                $('#customerCount').text(response.customer_count);
                $('#sales').text('₹' + response.sales);
                $('#transactionin').text('₹' + response.transactionin);
                $('#transactionout').text('₹' + response.transactionout);
                $('#salesAmountCash').text('₹' + response.salesAmountCash);
                $('#salesAmountOnline').text('₹' + response.salesAmountOnline);
                $('#deductionAmountCash').text('₹' + response.deductionAmountCash);
                $('#deductionAmountOnline').text('₹' + response.deductionAmountOnline);
                $('#totalStock').text(response.total_stock);
                $('#totalPurchases').text('₹' + response.total_purchases);
            },
            error: function(xhr) {
                console.log('Dashboard data error:', xhr.responseText);
            }
        });
    }

    function fetchAllTransactions() {
        $.ajax({
            url: "{{ route('transcation.display') }}",
            method: "GET",
            data: {
                date_from: currentDateFrom,
                date_to: currentDateTo
            },
            success: function(response) {
                if (!response.success) return;
                
                renderTransactionsIn(response.transactionsIn);
                renderTransactionsOut(response.transactionsOut);
                renderSalesTransactions(response.sales_transactions);
                renderEmiTransactions(response.emi_transactions);
            },
            error: function(xhr) {
                console.log('Transactions error:', xhr.responseText);
            }
        });
    }

    function renderTransactionsIn(data) {
        const tbody = $("#transactionListIn");
        tbody.empty();
        
        if (!data || data.length === 0) {
            tbody.append("<tr><td colspan='5' class='text-center'>No transactions found</td></tr>");
            return;
        }
        
        $.each(data, function(index, transaction) {
            tbody.append(`
                <tr>
                    <td>${index + 1}</td>
                    <td>₹${parseFloat(transaction.amount).toLocaleString('en-IN')}</td>
                    <td>${getPaymentModeName(transaction.payment_mode)}</td>
                    <td>${transaction.remark || '-'}</td>
                    <td>
                        <a href="{{ route('admin.transaction.index') }}" class="btn btn-sm btn-info">
                            <i class="fa fa-eye"></i>
                        </a>
                    </td>
                </tr>
            `);
        });
    }

    function renderTransactionsOut(data) {
        const tbody = $("#transactionListOut");
        tbody.empty();
        
        if (!data || data.length === 0) {
            tbody.append("<tr><td colspan='5' class='text-center'>No transactions found</td></tr>");
            return;
        }
        
        $.each(data, function(index, transaction) {
            tbody.append(`
                <tr>
                    <td>${index + 1}</td>
                    <td>₹${parseFloat(transaction.amount).toLocaleString('en-IN')}</td>
                    <td>${getPaymentModeName(transaction.payment_mode)}</td>
                    <td>${transaction.remark || '-'}</td>
                    <td>
                        <a href="{{ route('admin.transaction.index') }}" class="btn btn-sm btn-info">
                            <i class="fa fa-eye"></i>
                        </a>
                    </td>
                </tr>
            `);
        });
    }

    function renderSalesTransactions(data) {
        const tbody = $("#salesTransactionList");
        tbody.empty();
        
        if (!data || data.length === 0) {
            tbody.append("<tr><td colspan='5' class='text-center'>No Sales Found</td></tr>");
            return;
        }
        
        $.each(data, function(index, transaction) {
            console.log(transaction);
            tbody.append(`
                <tr>
                    <td>${index + 1}</td>
                    <td>₹${parseFloat(transaction.amount).toLocaleString('en-IN')}</td>
                    <td>${transaction.customer_name || '-'}</td>
                    <td>${getPaymentModeName(transaction.payment_mode)}</td>
                    <td>
                        <a href="{{ route('admin.sale.index') }}" class="btn btn-sm btn-info">
                            <i class="fa fa-eye"></i>
                        </a>
                    </td>
                </tr>
            `);
        });
    }

    function renderEmiTransactions(data) {
        const tbody = $("#emiTransactionList");
        tbody.empty();
        
        if (!data || data.length === 0) {
            tbody.append("<tr><td colspan='5' class='text-center'>No EMI Found</td></tr>");
            return;
        }
        
        $.each(data, function(index, transaction) {
            tbody.append(`
                <tr>
                    <td>${index + 1}</td>
                    <td>₹${parseFloat(transaction.amount).toLocaleString('en-IN')}</td>
                    <td>${transaction.customer_name || '-'}</td>
                    <td>${getPaymentModeName(transaction.payment_mode)}</td>
                    <td>
                        <a href="{{ route('admin.deduction.index') }}" class="btn btn-sm btn-info">
                            <i class="fa fa-eye"></i>
                        </a>
                    </td>
                </tr>
            `);
        });
    }

    function getPaymentModeName(mode) {
        return parseInt(mode) === 1 ? 'Cash' : 'Online';
    }

    $(document).ready(function() {
        // Initial load
        fetchDashboardData();
        fetchAllTransactions();
        
        // Auto refresh every 30 seconds
        setInterval(function() {
            fetchDashboardData();
            fetchAllTransactions();
        }, 30000);
    });
</script>
@endsection