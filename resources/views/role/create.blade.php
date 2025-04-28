@extends('layouts.app')
{{-- @if(Auth::guard('admin')->check()) --}}
@section('title','Admin Panel')

{{-- @endif --}}

@section('content-page')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Role</h3>
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
                        <a href="{{ route('admin.role.index')}}">Role</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Add Role</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Add Role</div>
                        </div>
                        <form method="POST" action="{{ route('admin.role.store') }}" id="roleForm">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="driver_name">Role<span style="color: red">*</span></label>
                                            <input type="text" class="form-control" name="name" id="name" placeholder="Enter Role Name" required />
                                            @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="card-header">
                                    <div class="form-group">
                                        <label for="driver_name">Permissions:</label>
                                            <div class="form-check-inline float-end">
                                                <input class="form-check-input" type="checkbox"  id="select-all" name="select-all">
                                                <label class="form-check-label" for="select-all">Select All</label>
                                            </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($permissions as $permission)
                                            <div class="col-2">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                           id="{{ $permission->name }}" name="permissions[]"
                                                           value="{{ $permission->name }}">
                                                    <label class="form-check-label" for="{{ $permission->name }}">{{ $permission->name }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                            </div>
                            <div class="card-action">
                                <button class="btn btn-success" type="submit">Submit</button>
                                <a href="{{ route('admin.role.index') }}" class="btn btn-danger">Cancel</a>
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
    document.getElementById('select-all').addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
    $(document).ready(function () {
        $('#roleForm').validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2,
                   unique:true
                }
    },
            messages: {
                name: {
                    required: "Please enter a role name",
                    minlength: "Brand name must be at least 2 characters long",
                    unique: "The name is already has been taken"
                }
            },
            errorElement: 'span',
            errorClass: 'text-danger',
            highlight: function (element, errorClass) {
                $(element).addClass("is-invalid");


            },
            unhighlight: function (element, errorClass) {
                $(element).removeClass("is-invalid");
            }
            });

    });
</script>
@endsection
