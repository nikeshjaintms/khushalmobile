@extends('layouts.app')

@section('title', 'Admin Panel')

@section('content-page')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Deductions</h3>
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
                        <a href="{{ route('admin.deduction.index') }}">Deductions</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Deductions</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('admin.deduction.create') }}" class="float-end btn btn-sm btn-rounded btn-primary">
                                <i class="fas fa-plus"></i> Deduction
                            </a>
                            <h4 class="card-title">Deductions</h4>
                        </div>
                        <div class="card-body">
                            <!-- Filter Form -->
                            <div class="filter-section mb-4 p-3 border rounded bg-light">
                                <h5 class="mb-3">Filter Deductions</h5>
                                <form method="GET" action="{{ route('admin.deduction.index') }}" class="row" id="filterForm">
                                    <div class="col-md-3 mb-2">
                                        <input type="text" name="customer_name" class="form-control" placeholder="Customer Name" value="{{ request('customer_name') }}">
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <input type="text" name="phone" class="form-control" placeholder="Mobile Number" value="{{ request('phone') }}">
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <input type="text" name="city" class="form-control" placeholder="City" value="{{ request('city') }}">
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <select name="status" class="form-control">
                                            <option value="">All Status</option>
                                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <input type="number" name="emi_from" class="form-control" placeholder="EMI Paid From" value="{{ request('emi_from') }}">
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <input type="number" name="emi_to" class="form-control" placeholder="EMI Paid To" value="{{ request('emi_to') }}">
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <button type="submit" class="btn btn-primary w-100">Apply Filter</button>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <a href="{{ route('admin.deduction.index') }}" class="btn btn-secondary w-100">Reset</a>
                                    </div>
                                </form>
                            </div>

                            <div class="table-responsive">
                                <table id="deduction-datatables" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Name</th>
                                            <th>Mobile Number</th>
                                            <th>City</th>
                                            <th>Total EMI</th>
                                            <th>Total Paid</th>
                                            <th>Remaining EMI</th>
                                            <th>Down Payment</th>
                                            <th>EMI Amount</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($finances as $index => $finance)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $finance->customer_name }}</td>
                                            <td>{{ $finance->phone }}</td>
                                            <td>{{ $finance->city }}</td>
                                            <td>{{ $finance->month_duration }}</td>
                                            <td>{{ $finance->paid_emi_count }}</td>
                                            <td>{{ $finance->remaining_emi_count }}</td>
                                            <td>{{ number_format($finance->downpayment, 2) }}</td>
                                            <td>{{ number_format($finance->emi_value, 2) }}</td>
                                            <td>
                                                @if($finance->status == 'paid')
                                                    <span class="badge badge-success">{{ Str::upper($finance->status) }}</span>
                                                @else
                                                    <span class="badge badge-danger">{{ Str::upper($finance->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.deduction.show', ['customerId' => $finance->customer_id, 'financeId' => $finance->id]) }}" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="fa fa-eye"></i> View
                                                </a>
                                                @can('delete-deduction')
                                                    <button onclick="deleteDeduction({{ $finance->id }})" class="btn btn-sm btn-danger">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                @endcan
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="11" class="text-center">No data available</td>
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
            // Check if DataTable is already initialized
            var table = null;
            var tableId = '#deduction-datatables';
            
            if ($.fn.DataTable.isDataTable(tableId)) {
                table = $(tableId).DataTable();
                console.log('DataTable already initialized, reusing instance');
            } else {
                // Initialize DataTable if not already initialized
                table = $(tableId).DataTable({
                    order: [[0, 'desc']],
                    pageLength: 10,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    language: {
                        search: "Search:",
                        searchPlaceholder: "Type to search...",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        infoEmpty: "Showing 0 to 0 of 0 entries",
                        zeroRecords: "No matching records found"
                    }
                });
                console.log('DataTable initialized successfully');
            }
        });
        
        function deleteDeduction(id) {
            var url = '{{ route("admin.deduction.delete", "id") }}'.replace("id", id);
            
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
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.status == 'success') {
                                Swal.fire('Deleted!', 'Deduction Deleted Successfully.', 'success').then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire('Failed!', 'Failed to delete Deduction.', 'error');
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