@extends('layouts.app')

@section('title', 'Dealer Outstanding Report')

@section('content-page')
<style>
@media print {
    .btn, .dataTables_filter, .dataTables_length, .dataTables_paginate {
        display: none !important;
    }
    .card-header .d-flex {
        display: block !important;
    }
}
.text-end {
    text-align: right !important;
}
</style>
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Dealer Outstanding Report</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ route('dashboard') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Reports</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Dealer Outstanding</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Outstanding Payments to Dealers</h4>
                            <div>
                                {{-- <button onclick="window.print()" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-print"></i> Print
                                </button> --}}
                                <button onclick="exportToExcel()" class="btn btn-sm btn-success">
                                    <i class="fas fa-file-excel"></i> Export Excel
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Summary Cards -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h5>Total Outstanding</h5>
                                        <h3 class="text-danger">₹ {{ number_format($totalOutstanding, 2) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h5>Total Purchase Value</h5>
                                        <h3 class="text-primary">₹ {{ number_format($totalPurchaseValue, 2) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h5>Number of Dealers</h5>
                                        <h3 class="text-info">{{ count($dealerOutstanding) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="basic-datatables" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Dealer Name</th>
                                        {{-- <th>Contact</th>
                                        <th>Email</th> --}}
                                        <th>Total Purchases (₹)</th>
                                        <th>Total Paid (₹)</th>
                                        <th>Outstanding (₹)</th>
                                        <th>Purchase Orders</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($dealerOutstanding as $index => $dealer)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $dealer->dealer_name }}</strong>
                                            </td>
                                            {{-- <td>{{ $dealer->dealer_contact }}</td>
                                            <td>{{ $dealer->dealer_email }}</td> --}}
                                            <td class="text-end">₹ {{ number_format($dealer->total_purchase_amount, 2) }}</td>
                                            <td class="text-end">₹ {{ number_format($dealer->total_paid_amount, 2) }}</td>
                                            <td class="text-end text-danger fw-bold">₹ {{ number_format($dealer->outstanding_amount, 2) }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-info">{{ $dealer->purchase_count }}</span>
                                            </td>
                                            <td>
                                                <button type="button" 
                                                        class="btn btn-sm btn-primary view-dealer-purchases" 
                                                        data-dealer-id="{{ $dealer->dealer_id }}"
                                                        data-dealer-name="{{ $dealer->dealer_name }}">
                                                    <i class="fas fa-eye"></i> View Purchases
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-success">
                                                <i class="fas fa-check-circle"></i> No outstanding payments to dealers!
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr class="fw-bold">
                                        <td colspan="2" class="text-end">Total:</td>
                                        <td class="text-end">₹ {{ number_format($totalPurchaseValue, 2) }}</td>
                                        <td class="text-end">-</td>
                                        <td class="text-end text-danger">₹ {{ number_format($totalOutstanding, 2) }}</td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for viewing dealer purchases -->
<div class="modal fade" id="dealerPurchasesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Purchase Orders - <span id="dealerName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>PO No.</th>
                                <th>PO Date</th>
                                <th>Sub Total</th>
                                <th>Tax</th>
                                <th>Total</th>
                                {{-- <th>Status</th> --}}
                            </tr>
                        </thead>
                        <tbody id="purchasesList">
                            <tr>
                                <td colspan="6" class="text-center">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer-script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    $('#dealer-outstanding-table').DataTable({
        pageLength: 25,
        ordering: true,
        pagination: true,
        order: [[6, 'desc']], // Sort by outstanding amount descending
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries"
        }
    });
});

// View dealer purchases
$('.view-dealer-purchases').click(function() {
    var dealerId = $(this).data('dealer-id');
    var dealerName = $(this).data('dealer-name');
    
    $('#dealerName').text(dealerName);
    $('#dealerPurchasesModal').modal('show');
    
    // Fetch purchases via AJAX
    $.ajax({
        url: '{{ route("admin.report.dealer.purchases") }}',
        type: 'GET',
        data: { dealer_id: dealerId },
        success: function(response) {
            var html = '';
            if (response.purchases && response.purchases.length > 0) {
                $.each(response.purchases, function(index, purchase) {
                    html += '<tr>';
                    html += '<td>' + purchase.po_no + '</td>';
                    html += '<td>' + purchase.po_date + '</td>';
                    html += '<td class="text-end">₹ ' + parseFloat(purchase.sub_total).toLocaleString('en-IN', {minimumFractionDigits: 2}) + '</td>';
                    html += '<td class="text-end">₹ ' + parseFloat(purchase.total_tax_amount).toLocaleString('en-IN', {minimumFractionDigits: 2}) + '</td>';
                    html += '<td class="text-end">₹ ' + parseFloat(purchase.total).toLocaleString('en-IN', {minimumFractionDigits: 2}) + '</td>';
                    // html += '<td><span class="badge bg-success">Completed</span></td>';
                    html += '</tr>';
                });
            } else {
                html = '<tr><td colspan="6" class="text-center">No purchase orders found for this dealer.</td></tr>';
            }
            $('#purchasesList').html(html);
        },
        error: function() {
            $('#purchasesList').html('<tr><td colspan="6" class="text-center text-danger">Error loading purchase orders.</td></tr>');
        }
    });
});

// Export to Excel
function exportToExcel() {
    var table = document.getElementById('dealer-outstanding-table');
    var html = table.outerHTML;
    var url = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
    var link = document.createElement('a');
    link.setAttribute('href', url);
    link.setAttribute('download', 'dealer_outstanding_report.xls');
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>



@endsection