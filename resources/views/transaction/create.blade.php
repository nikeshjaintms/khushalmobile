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
                            <div class="card-title">Add Transaction</div>
                        </div>
                        <form method="POST" action="{{ route('admin.transaction.store') }}" id="transactionForm">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type">Payment Mode <span style="color: red">*</span></label>
                                            <select name="payment_mode" id="payment_mode" class="form-control" required>
                                                <option value=""> Select Mode</option>
                                                <option value="1">Cash</option>
                                                <option value="2">Online</option>
                                                <option value="3">Finance</option>
                                            </select>
                                            @error('payment_mode')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="amount">Amount<span style="color: red">*</span></label>
                                            <input type="number" class="form-control" name="amount" id="amount"
                                                placeholder="Enter Amount Name" required />
                                            @error('amount')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="remark">Reference no <span style="color: red">*</span></label>
                                            <input type="text" class="form-control" name="reference_no" id="reference_no"
                                                placeholder="Enter Reference no" />
                                            @error('reference_no')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type">Transactiontype <span style="color: red">*</span></label>
                                            <select name="type" id="type" class="form-control" required>
                                                <option value="">Select Transaction Type</option>
                                                <option value="in">IN</option>
                                                <option value="out">OUT</option>
                                            </select>
                                            @error('type')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="remark">Remark <span style="color: red">*</span></label>
                                            <input type="text" class="form-control" name="remark" id="remark"
                                                placeholder="Enter Remark" />
                                            @error('remark')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-action">
                                <button class="btn btn-success" type="submit">Submit</button>
                                <a href="{{ route('admin.transaction.index') }}" class="btn btn-danger">Cancel</a>
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
                    payment_mode:{
                        required: true,
                    },
                    // reference_no:{
                    //     minlength: 5,
                    // },
                    amount: {
                        required: true,
                        number: true,
                        min: 0
                    },
                    type: {
                        required: true,
                    },
                    // remark: {
                    //     minlength: 2
                    // }
                },
                messages: {
                    payment_mode:{
                        required: "Please select a Payment Mode",
                    },
                    // reference_no:{
                    //     minlength: "Please enter at least 5 characters",
                    // },
                    amount: {
                        required: "Please enter a Amount",
                        number: "Please enter a valid number",
                        min: "Please enter a positive number"
                    },
                    type: {
                        required: "Please enter a Transaction Type",
                    },
                    // remark: {
                    //     minlength: "Remark must be at least 2 characters long"
                    // }
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
