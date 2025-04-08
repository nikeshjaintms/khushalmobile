@extends('layouts.app')
{{-- @if(Auth::guard('admin')->check()) --}}
@section('title','Admin Panel')

{{-- @endif --}}

@section('content-page')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Product</h3>
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
                        <a href="{{ route('admin.product.index')}}">Product</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Edit Product</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Edit Product</div>
                        </div>
                        <form method="POST" action="{{ route('admin.product.update', $product->id ) }}" id="productForm">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="brand_id">Brand<span style="color: red">*</span></label>
                                           <select name="brand_id" id="brand_id" class="form-control">
                                            <option value="">Select Brand</option>
                                            @foreach($brands as $brand)
                                                <option
                                                {{ $product->brand_id == $brand->id ? 'selected':'' }}
                                                value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                           </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="product_name">Product Name<span style="color: red">*</span></label>
                                            <input type="text" class="form-control" name="product_name" value="{{ $product->product_name }}" id="product_name" placeholder="Enter Product Name" required />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="mrp">MRP<span style="color: red">*</span></label>
                                            <input type="number" class="form-control" name="mrp" id="mrp" value="{{ $product->mrp }}" placeholder="Enter MRP " required />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-action">
                                <button class="btn btn-success" type="submit">Submit</button>
                                <a href="{{ route('admin.product.index') }}" class="btn btn-danger">Cancel</a>
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
    $(document).ready(function () {
        $("#productForm").validate({
            onfocusout: function (element) {
                this.element(element); // Validate the field on blur
            },
            onkeyup: false, // Optional: Disable validation on keyup for performance
            rules: {
                brand_id: {
                    required: true,
                },
                product_name: {
                    required: true,
                    minlength: 3,
                    maxlength: 50,
                },
                mrp:{
                    required: true,
                    number: true,
                    min: 1,
                },
            },
            messages: {
                brand_id:{
                    required: "Please Select a Brand",
                },
                product_name: {
                    required: "Please Enter Product Name",
                    minleght: "Please Enter Minimum 3 Characters",
                    maxlength: "Please Enter Maximum 50 Characters",
                },
                mrp: {
                    required: "Please Enter a MRP Value",
                    number: "Please enter a valid number",
                    min: "MRP must be greater than 1"
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
