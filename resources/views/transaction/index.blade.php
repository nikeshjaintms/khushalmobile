@extends('layouts.app')
@section('title', 'Admin Panel')

@section('content-page')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Transaction</h3>
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
                        <a href="{{ route('admin.transaction.index') }}">Transaction</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Transaction</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            @can('create-transaction')
                                <a href="{{ route('admin.transaction.create') }}" class="float-end btn btn-sm btn-rounded btn-primary">
                                    <i class="fas fa-plus"></i> Transaction
                                </a>
                            @endcan
                            <h4 class="card-title">Transaction</h4>
                        </div>
                        <div class="card-body">
                            <!-- Filter Form -->
                            <div class="filter-section mb-4 p-3 border rounded bg-light">
                                <h5 class="mb-3">Filter Transactions</h5>
                                <form method="GET" action="{{ route('admin.transaction.index') }}" class="row" id="filterForm">
                                    <div class="col-md-2 mb-2">
                                        <input type="text" name="amount" class="form-control" placeholder="Amount" value="{{ request('amount') }}">
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <select name="type" class="form-control">
                                            <option value="">All Types</option>
                                            <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>In</option>
                                            <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Out</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <input type="text" name="reference_no" class="form-control" placeholder="Reference No" value="{{ request('reference_no') }}">
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <input type="text" name="remark" class="form-control" placeholder="Remark" value="{{ request('remark') }}">
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <input type="date" name="date_from" class="form-control" placeholder="Date From" value="{{ request('date_from') }}">
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <input type="date" name="date_to" class="form-control" placeholder="Date To" value="{{ request('date_to') }}">
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <button type="submit" class="btn btn-primary w-100">Apply Filter</button>
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <a href="{{ route('admin.transaction.index') }}" class="btn btn-secondary w-100">Reset</a>
                                    </div>
                                </form>
                            </div>

                            <div class="table-responsive">
                                <table id="transaction-datatables" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Amount</th>
                                            <th>Type</th>
                                            <th>Reference No</th>
                                            <th>Remark</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($transctions as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ number_format($item->amount, 2) }}</td>
                                            <td>
                                                @if($item->type == 'in')
                                                    <span class="badge badge-success">{{ Str::upper($item->type) }}</span>
                                                @elseif($item->type == 'out')
                                                    <span class="badge badge-danger">{{ Str::upper($item->type) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->reference_no ?? '-' }}</td>
                                            <td>{{ $item->remark ?? '-' }}</td>
                                            <td>{{ $item->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                @can('edit-transaction')
                                                    <a href="{{ route('admin.transaction.edit', $item->id) }}" class="btn btn-link btn-primary" title="Edit">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endcan
                                                @can('delete-transaction')
                                                    <button onclick="deleteTransaction({{ $item->id }})" class="btn btn-link btn-danger" title="Delete">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                @endcan
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No data available</td>
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
            var tableId = '#transaction-datatables';
            
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
        
        function deleteTransaction(id) {
            var url = '{{ route("admin.transaction.delete", "id") }}'.replace("id", id);
            
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
                                Swal.fire('Deleted!', response.message, 'success').then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire('Failed!', 'Failed to delete Transaction.', 'error');
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