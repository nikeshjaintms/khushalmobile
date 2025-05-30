@extends('layouts.app')
{{-- @if(Auth::guard('admin')->check()) --}}
@section('title','Admin Panel')

{{-- @endif --}}

@section('content-page')

    {{-- @if (!empty($alerts))
        <script>
            window.onload = function() {
                let alerts = @json($alerts);
                console.log(alerts);
                alerts.forEach(function(alert) {
                    Swal.fire({
                        title: 'Reminder',
                        text: alert,
                        icon: 'info',
                        confirmButtonText: 'Okay'
                    });
                });
            };
        </script>
    @endif --}}
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
                                <a href="{{ route('admin.transaction.create') }}" class=" float-end btn btn-sm btn-rounded btn-primary"><i class="fas fa-plus"></i> Transaction</a>
                            @endcan
                            <h4 class="card-title">Transaction</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Amount</th>
                                        <th>Type</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($transctions as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{$item->amount }}</td>
                                            <td>
                                                @if($item->type == 'in')
                                                    <span class="badge badge-success">
                                    {{ Str::upper($item->type) }}
                                </span>

                                                @elseif($item->type == 'out')
                                                    <span class="badge badge-danger">
                                    {{ Str::upper($item->type) }}
                                </span>
                                                @endif
                                            </td>
                                                <td>
                                                    @can('edit-transaction')
                                                        <a href="{{ route('admin.transaction.edit', $item->id) }}" class="btn btn-lg btn-link btn-primary">
                                                            <i class="fa fa-edit">
                                                            </i></a>
                                                    @endcan

                                                    @can('delete-transaction')
                                                        <button onclick="deletevehicle_info({{ $item->id }})" class="btn btn-link btn-danger">
                                                            <i class="fa fa-trash">
                                                            </i>
                                                        </button>
                                                    @endcan
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
                        success: function (response) {
                            if (response.status == 'success') {
                                Swal.fire(
                                    'Deleted!',
                                    response.message,
                                    'success'
                                ).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Failed!',
                                    'Failed to delete Transaction.',
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



