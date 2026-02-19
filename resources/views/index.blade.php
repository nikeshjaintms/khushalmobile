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

        .note-card input,
        .note-card textarea {
            width: 100%;
            border: none;
            outline: none;
            font-size: 16px;
            resize: none;
            background: transparent;
            margin-bottom: 8px;
        }

        .note-card.collapsed .note-title,
        .note-card.collapsed .note-actions {
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
    </style>

    <div class="container">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Dashboard</h3>
                </div>
                <div class="ms-md-auto py-2 py-md-0">
                    {{-- <a href="#" class="btn btn-label-info btn-round me-2">Manage</a> --}}
                    <a href="{{ route('admin.customer.create') }}" class="btn btn-primary btn-round">Add Customer</a>
                </div>
            </div>

            {{-- Stats section (as is) --}}
            <div class="row">
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-primary bubble-shadow-small">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Total Dealer</p>
                                        <h4 class="card-title" id="dealerCount">0</h4>
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
                                        <i class="fas fa-luggage-cart"></i>
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
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                        <i class="far fa-check-circle"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Today Transcation IN</p>
                                        <h4 id="transactionin">₹ 0</h4>
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
                                    <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                        <i class="far fa-check-circle"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Today Transcation OUT</p>
                                        <h4 id="transactionout">₹ 0</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-primary bubble-shadow-small">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Sales Cash Amount</p>
                                        <h4 class="card-title" id="salesAmountCash">0</h4>
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
                                        <i class="fas fa-luggage-cart"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category"> Sales Online Amount</p>
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
                                    <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                        <i class="far fa-check-circle"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Today Transcation In Online</p>
                                        <h4 id="transactionInOnline">₹ 0</h4>
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
                                    <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                        <i class="far fa-check-circle"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Today Transcation In Cash</p>
                                        <h4 id="transactionInCash">₹ 0</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-primary bubble-shadow-small">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Deductions Cash Amount</p>
                                        <h4 class="card-title" id="deductionAmountCash">0</h4>
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
                                        <i class="fas fa-luggage-cart"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category"> Deductions Online Amount</p>
                                        <h4 id="deductionAmountOnline">₹0</h4>
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
                                    <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                        <i class="far fa-check-circle"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Today Transcation Out Online</p>
                                        <h4 id="transactionOutOnline">₹ 0</h4>
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
                                    <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                        <i class="far fa-check-circle"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Today Transcation Out Cash</p>
                                        <h4 id="transactionOutCash">₹ 0</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <strong><h4 style="text-align: center;margin-top: .5rem;">Today's Transactions (IN)</h4></strong>
                        <div class="card-body" style="height: 20em; overflow-y: scroll;">
                            <table class="table table-striped" >

                                <thead>
                                    <tr>
                                        <th>Sr no</th>
                                        <th>Amount</th>
                                        <th>Payment Mode</th>
                                        <th>Remark</th>
                                    </tr>
                                </thead>
                                <tbody id="transactionListIn" >
                                    <!-- Notes will be dynamically added here -->
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <strong><h4 style="text-align: center;margin-top: .5rem;">Today's Transactions (OUT)</h4></strong>
                        <div class="card-body" style="height: 20em; overflow-y: scroll;">
                            <table class="table table-striped"  >

                                <thead>
                                    <tr>
                                        <th>Sr no</th>
                                        <th>Amount</th>
                                        <th>Payment Mode</th>
                                        <th>Remark</th>
                                    </tr>
                                </thead>
                                <tbody id="transactionListOut" >
                                    <!-- Notes will be dynamically added here -->
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <h4 id="transactionTitle" style="text-align: center; margin-top: .5rem;">Sales Transactions</h4>
                        <div class="card-body" style="height: 20em; overflow-y: scroll;">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Sr no</th>
                                        <th>Amount</th>
                                        <th>Payment Mode</th>
                                        <th>Reference No</th>
                                    </tr>
                                </thead>
                                <tbody id="salesTransactionList" >
                                    <!-- Notes will be dynamically added here -->
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <h4 id="transactionTitle" style="text-align: center; margin-top: .5rem;">EMI Transactions</h4>
                        <div class="card-body" style="height: 20em; overflow-y: scroll;">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Sr no</th>
                                        <th>Amount</th>
                                        <th>Payment Mode</th>
                                        <th>Reference No</th>
                                    </tr>
                                </thead>
                                <tbody id="emiTransactionList" >
                                    <!-- Notes will be dynamically added here -->
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>



            {{-- Notes Section --}}
            {{-- <form id="noteForm">
                @csrf
                <div class="note-card collapsed" id="noteCard">
                    <input type="text" name="title" placeholder="Add title" class="note-title" id="noteTitle">
                    <textarea name="description" rows="1" placeholder="Take a note..." id="noteDescription"></textarea>
                    <div class="note-actions">
                        <button type="submit">Save</button>
                        <button type="button" onclick="closeNote()">Close</button>
                    </div>
                </div>
            </form> --}}
        </div>
    </div>

    <script>



    $(document).ready(function () {

        function fetchTransactionsIn() {
            $.ajax({
                url: "{{ route('transcation.display') }}",
                method: "GET",
                success: function (response) {
                    if (response.success) {
                        var transactionsIn = response.transactionsIn;
                        var tbody = $("#transactionListIn");

                        tbody.empty();

                        if (transactionsIn.length === 0) {
                            tbody.append("<tr><td colspan='4' class='text-center'>No transactions found</td></tr>");
                        } else {
                            $.each(transactionsIn, function (index, transaction) {
                                tbody.append(`
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${transaction.amount ?? '-'}</td>
                                        <td>${getPaymentModeName(transaction.payment_mode)}</td>
                                        <td>${transaction.remark ?? '-'}</td>
                                    </tr>
                                `);
                            });
                        }
                    }
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

        function fetchTransactionsOut() {
            $.ajax({
                url: "{{ route('transcation.display') }}",
                method: "GET",
                success: function (response) {
                    if (response.success) {
                        var transactionsOut = response.transactionsOut;
                        var tbody = $("#transactionListOut");

                        tbody.empty();

                        if (transactionsOut.length === 0) {
                            tbody.append("<tr><td colspan='4' class='text-center'>No transactions found</td></tr>");
                        } else {
                            $.each(transactionsOut, function (index, transaction) {
                                tbody.append(`
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${transaction.amount ?? '-'}</td>
                                        <td>${getPaymentModeName(transaction.payment_mode)}</td>
                                        <td>${transaction.remark ?? '-'}</td>
                                    </tr>
                                `);
                            });
                        }
                    }
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        }


        {{-- function fetchSalesTransactions() {--}}
        {{--    $.ajax({--}}
        {{--        url: "{{ route('transcation.display') }}",--}}
        {{--        method: "GET",--}}
        {{--        success: function (response) {--}}
        {{--            if (response.success) {--}}
        {{--                var sales_transactions = response.sales_transactions;--}}
        {{--                var tbody = $("#salesTransactionList");--}}

        {{--                tbody.empty();--}}

        {{--                if (sales_transactions.length === 0) {--}}
        {{--                    tbody.append("<tr><td colspan='4' class='text-center'>No transactions found</td></tr>");--}}
        {{--                } else {--}}
        {{--                    $.each(sales_transactions, function (index, transaction) {--}}
        {{--                        tbody.append(`--}}
        {{--                            <tr>--}}
        {{--                                <td>${index + 1}</td>--}}
        {{--                                <td>${transaction.amount ?? '-'}</td>--}}
        {{--                                <td>${getPaymentModeName(transaction.payment_mode)}</td>--}}
        {{--                                <td>${transaction.reference_no ?? '-'}</td>--}}
        {{--                            </tr>--}}
        {{--                        `);--}}
        {{--                    });--}}
        {{--                }--}}
        {{--            }--}}
        {{--        },--}}
        {{--        error: function (xhr) {--}}
        {{--            console.log(xhr.responseText);--}}
        {{--        }--}}
        {{--    });--}}
        {{--}--}}

        // function fetchTransactions(type) {
        //     $.ajax({
        //         url: "{{ route('transcation.display') }}",
        //         method: "GET",
        //         success: function (response) {
        //             if (response.success) {
        //                 var tbody = $("#salesTransactionList");
        //                 tbody.empty();

        //                 let transactions = [];

        //                 if (type === "sales") {
        //                     transactions = response.sales_transactions;
        //                 } else if (type === "deductions") {
        //                     // transactions = response.transactionsOut;
        //                     transactions = [...response.transactionsOut, ...response.transactionsIn];
        //                 }

        //                 if (transactions.length === 0) {
        //                     tbody.append("<tr><td colspan='4' class='text-center'>No transactions found</td></tr>");
        //                 } else {
        //                     $('table').removeClass('d-none');
        //                     $.each(transactions, function (index, transaction) {
        //                         tbody.append(`

        //                     <tr>
        //                         <td>${index + 1}</td>
        //                         <td>${transaction.amount ?? '-'}</td>
        //                         <td>${getPaymentModeName(transaction.payment_mode)}</td>
        //                         <td>${transaction.reference_no ?? '-'}</td>
        //                     </tr>
        //                 `);
        //                     });
        //                 }
        //             }
        //         },
        //         error: function (xhr) {
        //             console.log(xhr.responseText);
        //         }
        //     });
        // }
        // $('#transaction_type').on('change', function () {
        //     const selectedType = $(this).val();
        //     if (selectedType === "sales" || selectedType === "deductions") {
        //         fetchTransactions(selectedType);
        //     } else {
        //         $("#salesTransactionList").empty();
        //     }
        // });
        // $('#transaction_type').on('change', function () {
        //     const selected = $(this).val();
        //     const title = $('#transactionTitle');

        //     if (selected === 'sales') {
        //         title.text('Sales Transactions');
        //     } else if (selected === 'deductions') {
        //         title.text('Emi Transactions');
        //     } else if (selected === 'all') {
        //         title.text(' Transactions');
        //     } else {
        //         title.text('Please select a transaction type');
        //         $('#salesTransactionList').html("<tr><td colspan='5' class='text-center'>Please select a transaction type</td></tr>");
        //     }
        // });

        // function getPaymentModeName(mode) {
        //     switch (parseInt(mode)) {
        //         case 1:
        //             return "Cash";
        //         case 2:
        //             return "Online";
        //         default:
        //             return "Unknown";
        //     }
        // }



    function fetchAllTransactions() {
        $.ajax({
            url: "{{ route('transcation.display') }}",
            method: "GET",
            success: function (response) {

                if (!response.success) return;

                renderSales(response.sales_transactions);
                renderEmi(response.emi_transactions);
            },
            error: function (xhr) {
                console.log(xhr.responseText);
            }
        });
    }

    function renderSales(data) {
        const tbody = $("#salesTransactionList");
        tbody.empty();

        if (!data || data.length === 0) {
            tbody.append("<tr><td colspan='4' class='text-center'>No Sales Found</td></tr>");
            return;
        }

        $.each(data, function (index, transaction) {
            tbody.append(`
                <tr>
                    <td>${index + 1}</td>
                    <td>${transaction.amount ?? '-'}</td>
                    <td>${getPaymentModeName(transaction.payment_mode)}</td>
                    <td>${transaction.reference_no ?? '-'}</td>
                </tr>
            `);
        });
    }

    function renderEmi(data) {
        const tbody = $("#emiTransactionList");
        tbody.empty();

        if (!data || data.length === 0) {
            tbody.append("<tr><td colspan='4' class='text-center'>No EMI Found</td></tr>");
            return;
        }

        $.each(data, function (index, transaction) {
            tbody.append(`
                <tr>
                    <td>${index + 1}</td>
                    <td>${transaction.amount ?? '-'}</td>
                    <td>${getPaymentModeName(transaction.payment_mode)}</td>
                    <td>${transaction.reference_no ?? '-'}</td>
                </tr>
            `);
        });
    }

    function getPaymentModeName(mode) {
        return parseInt(mode) === 1 ? 'Cash' : 'Online';
    }

    // Initial load
    fetchAllTransactions();

    // Auto refresh every 10 seconds
    setInterval(fetchAllTransactions, 10000);

        // Run once immediately
        fetchTransactionsIn();
        fetchTransactionsOut();
        fetchSalesTransactions();

        // Then run every 10 seconds
        setInterval(fetchTransactionsIn, 10000); // 10000 ms = 10 sec
        setInterval(fetchTransactionsOut, 10000); // 10000 ms = 10 sec
        setInterval(fetchSalesTransactions, 10000); // 10000 ms = 10 sec
    });
</script>
@endsection
