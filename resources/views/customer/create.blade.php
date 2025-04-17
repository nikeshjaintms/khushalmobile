@extends('layouts.app')
{{-- @if (Auth::guard('admin')->check()) --}}
@section('title', 'Admin Panel')

{{-- @endif --}}

@section('content-page')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Customer</h3>
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
                        <a href="{{ route('admin.customer.index') }}">Customer</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Add Customer</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Add Customer</div>
                        </div>
                        <form method="POST" action="{{ route('admin.customer.store') }}" id="customerForm">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Name<span style="color: red">*</span></label>
                                            <input type="text" class="form-control" name="name" id="name"
                                                placeholder="Enter Customer Name" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Phone<span style="color: red">*</span></label>
                                            <input type="text" class="form-control" name="phone" id="phone"
                                                placeholder="Enter Phone Number" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Address<span style="color: red">*</span></label>
                                            <textarea type="text" class="form-control" name="address" id="address" placeholder="Enter your address" required></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">City<span style="color: red">*</span></label>
                                            <input type="text" class="form-control" name="city" id="city"
                                                placeholder="Enter your city" required />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-action">
                                <button class="btn btn-success" type="submit">Submit</button>
                                <a href="{{ route('admin.customer.index') }}" class="btn btn-danger">Cancel</a>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/jquery.inputmask.min.js"></script>

    <script>
        $(document).ready(function() {

            $('input[name="phone"]').mask('0000000000');

            $('#name, #city').inputmask({
                regex: "^[a-zA-Z ]*$",
                placeholder: ''
            });

            $('#customerForm').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 2
                    },
                    phone: {
                        required: true
                    },

                    address: {
                        required: true
                    },
                    city: {
                        required: true
                    }

                },
                messages: {
                    name: {
                        required: "Please enter a customer name",
                        minlength: "customer name must be at least 2 characters long"
                    },
                    phone: {
                        required: "Please enter phone number"
                    },
                    address: {
                        required: "Please enter address"
                    },
                    city: {
                        required: "please enter city"
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
