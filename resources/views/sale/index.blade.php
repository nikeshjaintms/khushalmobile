@extends('layouts.app')
@section('title', 'Admin Panel')

@section('content-page')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Sale</h3>
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
                        <a href="{{ route('admin.sale.index') }}">Sale</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Sales</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            @can('create-sale')
                                <a href="{{ route('admin.sale.create') }}"
                                   class="float-end btn btn-sm btn-rounded btn-primary"><i class="fas fa-plus"></i>Sale</a>
                                <h4 class="card-title">Sale</h4>
                            @endcan
                        </div>
                        <div class="card-body">
                            <!-- Advanced Filter Section -->
                            <div class="filter-section mb-4 p-3 border rounded bg-light">
                                <h5 class="mb-3">Advanced Filters</h5>
                                <div class="row">
                                    <div class="col-md-3 mb-2">
                                        <input type="text" id="filter_customer" class="form-control" placeholder="Customer Name">
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <input type="text" id="filter_invoice" class="form-control" placeholder="Invoice No">
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <input type="text" id="filter_product" class="form-control" placeholder="Product">
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <input type="text" id="filter_imei" class="form-control" placeholder="IMEI">
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <select id="filter_status" class="form-control">
                                            <option value="">All Status</option>
                                            <option value="sold">Sold</option>
                                            <option value="return">Return</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <input type="date" id="filter_date_from" class="form-control" placeholder="Date From">
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <input type="date" id="filter_date_to" class="form-control" placeholder="Date To">
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <button id="reset_filters" class="btn btn-secondary w-100">Reset Filters</button>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="basic-datatables1" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Customer</th>
                                            <th>Invoice no</th>
                                            <th>Product</th>
                                            <th>IMEI</th>
                                            <th>Final Total amount</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($sales as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->customer->name }}</td>
                                            <td>{{ $item->invoice_no }}</td>
                                            <td>
                                                @foreach ($item->saleProducts as $productIndex => $product)
                                                    {{ $product->product->product_name ?? '-' }}@if(!$loop->last),@endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($item->saleProducts as $product)
                                                    {{ $product->purchaseProduct->imei ?? '-' }}
                                                @endforeach
                                            </td>
                                            <td>{{ number_format($item->total_amount, 2) }}</td>
                                            <td>
                                                @foreach ($item->saleProducts as $product)
                                                    @php $status = $product->purchaseProduct->status ?? null; @endphp
                                                    @if($status === 'sold')
                                                        <span class="badge bg-success">Sold</span>
                                                    @elseif($status === 'return')
                                                        <span class="badge bg-danger">Return</span>
                                                    @else
                                                        <span class="badge bg-secondary">-</span>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.invoice.index', $item->id) }}" class="btn btn-link btn-danger">
                                                    <i class="fa fa-file-pdf"></i>
                                                </a>
                                                @can('show-sale')
                                                    <a href="{{ route('admin.sale.show', $item->id) }}" class="btn btn-link btn-primary">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                @endcan
                                                @can('edit-sale')
                                                    <a href="{{ route('admin.sale.edit', $item->id) }}" class="btn btn-link btn-primary">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endcan
                                                @can('delete-sale')
                                                    <button onclick="deletesale_info({{ $item->id }})" class="btn btn-link btn-danger">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                @endcan
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No data available</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Custom filtering function for DataTable
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                var customer = $('#filter_customer').val().toLowerCase();
                var invoice = $('#filter_invoice').val().toLowerCase();
                var product = $('#filter_product').val().toLowerCase();
                var imei = $('#filter_imei').val().toLowerCase();
                var status = $('#filter_status').val().toLowerCase();
                var dateFrom = $('#filter_date_from').val();
                var dateTo = $('#filter_date_to').val();
                
                var rowCustomer = data[1].toLowerCase();
                var rowInvoice = data[2].toLowerCase();
                var rowProduct = data[3].toLowerCase();
                var rowImei = data[4].toLowerCase();
                var rowStatus = data[6].toLowerCase();
                var rowDate = $('table').DataTable().row(dataIndex).node().getAttribute('data-date') || '';
                
                if (customer && rowCustomer.indexOf(customer) === -1) return false;
                if (invoice && rowInvoice.indexOf(invoice) === -1) return false;
                if (product && rowProduct.indexOf(product) === -1) return false;
                if (imei && rowImei.indexOf(imei) === -1) return false;
                if (status && rowStatus.indexOf(status) === -1) return false;
                
                if (dateFrom && rowDate < dateFrom) return false;
                if (dateTo && rowDate > dateTo) return false;
                
                return true;
            });
            
            // Get DataTable instance
            var table = $('#basic-datatables1').DataTable();
            
            // Add date attributes to rows
            @foreach($sales as $item)
                var row = table.row($('tr:has(td:contains("{{ $item->invoice_no }}"))'));
                if (row.node()) {
                    $(row.node()).attr('data-date', '{{ date('Y-m-d', strtotime($item->created_at)) }}');
                }
            @endforeach
            
            // Apply filters on input change
            $('#filter_customer, #filter_invoice, #filter_product, #filter_imei, #filter_status, #filter_date_from, #filter_date_to').on('keyup change', function() {
                table.draw();
            });
            
            // Reset all filters
            $('#reset_filters').click(function() {
                $('#filter_customer, #filter_invoice, #filter_product, #filter_imei, #filter_status, #filter_date_from, #filter_date_to').val('');
                table.draw();
            });
        });
        
        function deletesale_info(id) {
            var url = '{{ route('admin.sale.delete', 'id') }}'.replace("id", id);
            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            id: id
                        },
                        success: function(response) {
                            if (response.status == 'success') {
                                Swal.fire('Deleted!', 'Sale has been deleted.', 'success').then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire('Failed!', 'Failed to delete Sale.', 'error');
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error!', 'An error occurred: ' + xhr.responseText, 'error');
                        }
                    });
                }
            });
        }
    </script>
@endsection