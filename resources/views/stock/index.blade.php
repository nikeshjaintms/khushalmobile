@extends('layouts.app')
{{-- @if (Auth::guard('admin')->check()) --}}
@section('title', 'Admin Panel')

{{-- @endif --}}

@section('content-page')

    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Stock</h3>
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
                        <a href="{{ route('admin.stock.index') }}">Stock</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Stock</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">

                                <a href="{{ route('admin.stock.create') }}"
                                   class=" float-end btn btn-sm btn-rounded btn-primary"><i class="fas fa-plus"></i>
                                    Add Stock</a>

                            <h4 class="card-title">Stocks</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Product Name</th>
                                        <th>Price</th>
                                        <th>IMEI</th>
                                        <th>Action</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($purchases as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->product->product_name }}</td>
                                            <td>{{ $item->price }}</td>
                                            <td>{{ $item->imei }}</td>
                                            <td>

{{--                                                    <a href="{{ route('admin.stock.show', $item->id) }}"--}}
{{--                                                       class="btn btn-lg btn-link btn-primary">--}}
{{--                                                        <i class="fa fa-eye">--}}
{{--                                                        </i></a>--}}
{{--                                               --}}
{{--                                               --}}
                                                    <a href="{{ route('admin.stock.edit', $item->id) }}"
                                                       class="btn btn-lg btn-link btn-primary">
                                                        <i class="fa fa-edit">
                                                        </i></a>

                                                    <button onclick="deletevehicle_info({{ $item->id }})"
                                                            class="btn btn-link btn-danger">
                                                        <i class="fa fa-trash">
                                                        </i>
                                                    </button>

                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No data available</td>
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
        function deletevehicle_info(id) {
            var url = '{{ route('admin.stock.delete', 'id') }}'.replace("id", id);

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
                                Swal.fire(
                                    'Deleted!',
                                    'Stock has been deleted.',
                                    'success'
                                ).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Failed!',
                                    'Failed to delete Brand.',
                                    'error'
                                );
                            }
                        },
                        error: function(xhr) {
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

