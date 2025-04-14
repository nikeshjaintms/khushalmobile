@extends('layouts.app')
{{-- @if(Auth::guard('admin')->check()) --}}
@section('title','Admin Panel')

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
                        <a href="{{ route('admin.customer.index')}}">Customer</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Edit Customer</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Edit Customer</div>
                        </div>
                        <form method="POST" action="{{ route('admin.customer.update', $data->id ) }}" id="customerForm">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div>
                                                <label for="name">Name<span style="color: red">*</span></label>
                                                <input type="text" class="form-control" name="name" value="{{ $data->name}}" id="name" placeholder="Enter Customer Name" required/>
                                            </div>


                                            <div>
                                                <label for="">Phone<span style="color: red">*</span></label>
                                                <input type="text" class="form-control" name="phone" value="{{$data->phone}}" id="phone" placeholder="Enter Phone Number" required/>
                                            </div>

                                            <div>
                                                <label for="">Address<span style="color: red">*</span></label>
                                                <textarea type="text" class="form-control" name="address"  id="address" placeholder="Enter your address" required>{{$data->address}}</textarea>
                                            </div>

                                            <div>
                                                <label for="">City<span style="color: red">*</span></label>
                                                <input type="text" class="form-control" name="city" value="{{$data->city}}" id="city" placeholder="Enter your city" required/>
                                            </div>

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
        $(document).ready(function () {

            $('input[name="phone"]').mask('0000000000');

            $('#name, #city').inputmask({
                regex: "^[a-zA-Z ]*$",
                placeholder: ''
            });

            $("#customerForm").validate({
                onfocusout: function (element) {
                    this.element(element); // Validate the field on blur
                },
                onkeyup: false, // Optional: Disable validation on keyup for performance
                rules: {
                    name: {
                        required: true,
                        minlength: 3,
                        maxlength: 50,
                    },
                    phone:{
                        required:true
                    },

                    address:{
                        required:true
                    },
                    city:{
                        required:true
                    }

                },
                messages: {
                    name: {
                        required: "Please Enter Customer Name",
                        minleght: "Please Enter Minimum 3 Characters",
                        maxlength: "Please Enter Maximum 50 Characters",
                    },
                    phone:{
                        required:"Please enter phone number"
                    },
                    address:{
                        required:"Please enter address"
                    },
                    city:{
                        required:"please enter city"
                    }
                },
                errorClass: "text-danger",
                errorElement: "span",
                highlight: function (element) {
                    $(element).addClass("is-invalid");
                },
                unhighlight: function (element) {
                    $(element).removeClass("is-invalid");
                },
                submitHandler: function (form) {
                    // Handle successful validation here
                    form.submit();
                }
            });
        });
    </script>
@endsection
