@extends('layouts.app')

@section('title','Admin Panel')


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
                        <a href="{{ route('admin.deduction.index')}}">Deductions</a>
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
                        <a href="{{ route('admin.deduction.create') }}" class=" float-end btn btn-sm btn-rounded btn-primary"><i class="fas fa-plus"></i> Deduction</a>
                        <h4 class="card-title">Deductions</h4>
                    </div>
                    <div class="card-body">
                      <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped table-hover">
                          <thead>
                            <tr>
                              <th>Sr No</th>
                              <th>Name</th>
                                <th>Down Payment</th>
                                <th>Finance Amount</th>
                              <th>EMI</th>
                              <th>Paid Amount</th>
                                <th>Remaining value</th>
                                <th>Total</th>
                              <th>Status</th>
{{--                              <th>Action</th>--}}

                            </tr>
                          </thead>
                          <tbody>
                            @forelse($deductions as $index => $item)
                                @foreach($finances as $finance)
                            <tr>
                              <td>{{ $index + 1 }}</td>
                              <td>{{$item->customer_name }}</td>
                                <td>{{$finance->downpayment}}</td>
                                <td>{{$finance->finance_amount}}</td>
                                <td>{{$finance->emi_value }}</td>
                                <td>{{$item->emi_value_paid }}</td>
                                <td>{{$item->remaining }}</td>
                                <td>{{$item->total }}</td>
                                <td>
                                    <span class="badge badge-success">
                                        {{ Str::upper($item->status) }}
                                    </span>


                                </td>
{{--                              <td>--}}
                                {{-- <a href="{{ route('admin.deduction.edit', $item->id) }}" class="btn btn-lg btn-link btn-primary">
                                  <i class="fa fa-edit">
                                </i></a> --}}
{{--                              </td>--}}
                            </tr>
                            @empty
                            <tr>
                              <td colspan="6" class="text-center">No data available</td>
                            </tr>
                            @endforelse
                                @endforeach
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
    function deletecustomer_info(id) {
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
                            Swal.fire(
                                'Deleted!',
                                'Deduction Deleted Successfully.',
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Failed!',
                                'Failed to delete Deduc tion.',
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



