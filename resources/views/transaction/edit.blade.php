@extends('layouts.app')
{{-- @if (Auth::guard('admin')->check()) --}}
@section('title', 'Admin Panel')

{{-- @endif --}}

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
                        <a href="#">Add Transaction</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Edit Transaction</div>
                        </div>
                        <form method="POST" action="{{ route('admin.transaction.update' , $transction->id ) }}" id="transactionForm">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="amount">Amount<span style="color: red">*</span></label>
                                            <input type="number" class="form-control" name="amount" id="amount"
                                                placeholder="Enter Amount Name" value="{{ $transction->amount }}" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type">Transactiontype <span style="color: red">*</span></label>
                                            <select name="type" id="type" class="form-control" required>
                                                <option value="">Select Transaction Type</option>
                                                <option value="in" {{ $transction->type == 'in' ? 'selected' : '' }}>IN</option>
                                                <option value="out" {{ $transction->type == 'out' ? 'selected' : '' }}>OUT</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="remark">Remark <span style="color: red">*</span></label>
                                            <input type="text" class="form-control" name="remark" id="remark"
                                                placeholder="Enter Remark" value="{{ $transction->remark }}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-action">
                                <button class="btn btn-success" type="submit">Submit</button>
                                <a href="{{ route('admin.brand.index') }}" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-script')
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#transactionForm').validate({
                onfocusout: function(element) {
                    this.element(element);
                },
                onkeyup: false,
                rules: {
                    amount: {
                        required: true,
                        number: true,
                        min: 0
                    },
                    type: {
                        required: true,
                    },
                    remark: {
                        minlength: 2
                    }
                },
                messages: {
                    amount: {
                        required: "Please enter a Amount",
                        number: "Please enter a valid number",
                        min: "Please enter a positive number"
                    },
                    type: {
                        required: "Please enter a Transaction Type",
                    },
                    remark: {
                        minlength: "Remark must be at least 2 characters long"
                    }
                },
                errorElement: 'span',
                errorClass: 'text-danger',
                highlight: function(element, errorClass) {
                    $(element).addClass("is-invalid");


                },
                unhighlight: function(element, errorClass) {
                    $(element).removeClass("is-invalid");
                }
            });

        });
    </script>
@endsection
