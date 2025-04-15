@extends('layouts.app')
{{-- @if(Auth::guard('admin')->check()) --}}
@section('title','Admin Panel')

{{-- @endif --}}

@section('content-page')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Sale</h3>
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
                        <a href="{{ route('admin.sale.index')}}">Sale</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Edit Sale</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Edit Sale</div>
                        </div>
                        <form method="POST" action="{{ route('admin.sale.update', $data->id ) }}" id="saleForm">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div>
                                                <label for="">Invoice No<span style="color: red">*</span></label>
                                                <input type="text" class="form-control" name="invoice_no" value="{{$data->invoice_no}}" id="invoice_no" placeholder="invoice no" required/>
                                            </div>

                                            <div>
                                                <label for="">Customer<span style="color: red">*</span></label>
                                                <select class="form-select" aria-label="Default select example">
                                                    <option selected> Select Customer</option>
                                                    @foreach($customers as $item)
                                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div>
                                                <label for="">Invoice Date<span style="color: red">*</span></label>
                                                <input id="datepicker" name="invoice_date" value="{{$data->invoice_date}}"  class="form-control datepicker" placeholder="select Date" />
                                            </div>

                                            <div>
                                                <label for="">Sub Total<span style="color: red">*</span></label>
                                                <input type="text" class="form-control" name="sub_total"  id="sub_total" placeholder="sub total" value="{{$data->sub_total}}" required/>
                                            </div>

                                            <div>
                                                <label for="">Tax Type<span style="color: red">*</span></label>
                                                <input type="text" class="form-control" name="tax_type"  value="{{$data->tax_type}}" id="tax_type" placeholder="Enter Tax Type" required/>
                                            </div>

                                            <div>
                                                <label for="">Tax<span style="color: red">*</span></label>
                                                <input type="text" class="form-control" name="tax" value="{{$data->tax}}"  id="tax" placeholder="Enter Tax" required/>
                                            </div>

                                            <div>
                                                <label for="">Tax Amount<span style="color: red">*</span></label>
                                                <input type="text" class="form-control" name="tax_amount"  value="{{$data->tax_amount}}" id="tax_amount" placeholder="Enter Tax Amount" required/>
                                            </div>

                                            <div>
                                                <label for="">Total Amount<span style="color: red">*</span></label>
                                                <input type="text" class="form-control" name="total_amount"  value="{{$data->total_amount}}" id="total_amount" placeholder="Enter Total  Amount" required/>
                                            </div>
                                            <div>
                                                <label for="">Payment Method<span style="color: red">*</span></label>
                                                <select class="form-select" aria-label="Default select example"  >
                                                    <option selected> Select Payment Method</option>
                                                    <option value="1">Online</option>
                                                    <option value="1">Cash</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label for="">Discount<span style="color: red">*</span></label>
                                                <input type="text" class="form-control" name="Discount"  id="Discount" placeholder="discount" required/>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-action">
                                <button class="btn btn-success" type="submit">Submit</button>
                                <a href="{{ route('admin.sale.index') }}" class="btn btn-danger">Cancel</a>
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
            $("#saleForm").validate({
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
                        required: "Please Enter Dealer Name",
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
