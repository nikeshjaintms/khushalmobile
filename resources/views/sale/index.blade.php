@extends('layouts.app')
{{-- @if (Auth::guard('admin')->check()) --}}
@section('title', 'Admin Panel')

{{-- @endif --}}

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
                                   class=" float-end btn btn-sm btn-rounded btn-primary"><i class="fas fa-plus"></i>Sale</a>
                                <h4 class="card-title">Sale</h4>
                            @endcan
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Customer</th>
                                        <th>Invoice no</th>
                                        <th>Product</th>
                                        <th>IMEI</th>
                                        <th>Final Total amount</th>

                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($sales as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->customer->name }}</td>
                                            <td>{{ $item->invoice_no }}</td>
                                            <td>
                                                @foreach ($item->saleProducts as $index => $product)
                                                    {{ $product->product->product_name ?? '-' }}@if (!$loop->last)
                                                        ,
                                                    @else
                                                        .
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>{{ $product->purchaseProduct->imei ?? '-' }}</td>
                                            <td>{{ $item->total_amount }}</td>
                                            <td>
                                                    <a href="{{ route('admin.invoice.index', $item->id) }}">
                                                        <i class="btn btn-link btn-danger">
                                                            <i class="fa fa-file-pdf"></i>
                                                        </i>
                                                    </a>
                                                @can('show-sale')
                                                    <a href="{{ route('admin.sale.show', $item->id) }}"
                                                       class="btn btn-lg btn-link btn-primary">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                @endcan

                                                @can('edit-sale')
                                                    <a href="{{ route('admin.sale.edit', $item->id) }}"
                                                       class="btn btn-lg btn-link btn-primary">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endcan
                                                @can('delete-sale')
                                                    <button onclick="deletesale_info({{ $item->id }})"
                                                            class="btn btn-link btn-danger delete-sale-row">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                @endcan
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No data available</td>
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
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            if (response.status == 'success') {
                                Swal.fire(
                                    'Deleted!',
                                    'Sale has been deleted.',
                                    'success'
                                ).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Failed!',
                                    'Failed to delete Sale.',
                                    'error'
                                );
                            }
                        },
                        error: function (xhr) {
                            Swal.fire(
                                'Error!',
                                'An error occurred: ' + xhr.responseText,
                                'error'
                            );
                        }
                    });
                }
            });
        }
    </script>

@endsection
