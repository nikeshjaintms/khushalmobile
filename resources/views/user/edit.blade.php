@extends('layouts.app')
{{-- @if (Auth::guard('admin')->check()) --}}
@section('title', 'Admin Panel')

{{-- @endif --}}

@section('content-page')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">User</h3>
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
                        <a href="{{ route('admin.user.index') }}">User</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Edit User</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Edit User</div>
                        </div>
                        <form method="POST" action="{{ route('admin.user.update', $data->id) }}" id="userForm">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Name<span style="color: red">*</span></label>
                                            <input type="text" class="form-control" name="name"
                                                value="{{ $data->name }}" id="name" placeholder="Enter Customer Name"
                                                required />
                                        </div>
                                        @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Email<span style="color: red">*</span></label>
                                            <input type="email" class="form-control" name="email"
                                                value="{{ $data->email }}" id="email" placeholder="Enter email"
                                                required />
                                            @error('email')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <label for="userRole" class="col-3 col-form-label">Roles</label>
                                        <select name="role" class="form-select" id="role">
                                            <option value="">Select</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->name }}" {{ $role->name == $roleName ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                            @error('role')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="card-action">
                                <button class="btn btn-success" type="submit">Submit</button>
                                <a href="{{ route('admin.user.index') }}" class="btn btn-danger">Cancel</a>
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
            $.validator.addMethod("regexEmail", function (value, element) {
                let regex = /^[^\s@]+@[^\s@]+\.[a-zA-Z]{2,}$/;
                return this.optional(element) || regex.test(value);
            });

            $('input[name="phone"]').mask('0000000000');

            $('#name, #city').inputmask({
                regex: "^[a-zA-Z ]*$",
                placeholder: ''
            });

            $('#email').on('keydown', function (e) {
                if (e.which === 32) {
                    e.preventDefault();
                }
            });
            $("#userForm").validate({
                onfocusout: function(element) {
                    this.element(element); // Validate the field on blur
                },
                onkeyup: false,
                rules: {
                    name: {
                        required: true,
                        minlength: 3,
                        maxlength: 50,
                    },
                    email:{
                        required: true,
                        regexEmail: true,

                    },

                    role:{
                        required:true
                    }
                },
                messages: {
                    name: {
                        required: "Please Enter User Name",
                        minleght: "Please Enter Minimum 3 Characters",
                        maxlength: "Please Enter Maximum 50 Characters",
                    },
                    email: {
                        required: "Please enter a Email",

                    },

                    role:{
                        required:"Please select a role"
                    }

                },
                errorClass: "text-danger",
                errorElement: "span",
                highlight: function(element) {
                    $(element).addClass("is-invalid");
                },
                unhighlight: function(element) {
                    $(element).removeClass("is-invalid");
                },
                submitHandler: function(form) {
                    // Handle successful validation here
                    form.submit();
                }
            });
        });
    </script>
@endsection
