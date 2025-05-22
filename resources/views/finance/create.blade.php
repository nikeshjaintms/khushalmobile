@extends('layouts.app')
{{-- @if (Auth::guard('admin')->check()) --}}
@section('title', 'Admin Panel')
{{-- @endif --}}
{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> --}}
@section('content-page')
    <link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <style>
        .table>tbody>tr>td,
        .table>tbody>tr>th {
            padding: 16px 5px !important;
        }


    </style>
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Finance</h3>
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
                        <a href="{{ route('admin.finance.index') }}">Finance</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Add Finance</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Add Sale</div>
                        </div>
                        <form method="POST" action="{{ route('admin.finance.store') }}" id="saleForm">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div>
                                                <label for="">Customer<span style="color: red">*</span></label>
                                                <select class="form-select customer" name="customer_id"
                                                        aria-label="Default select example">
                                                    <option selected value=""> Select Customer</option>
                                                    @foreach ($customers as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                    @error('customer_id')
                                                    <p style="color: red;">{{ $message }}</p>
                                                    @enderror
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3" id="finance_detail">
                                        <h4>Finance Details</h4>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> Ref Name </label>
                                                <input type="text" name="ref_name" id="ref_name"
                                                       class="form-control  ref_name" placeholder="Ref Name">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> Ref Mobile Number </label>
                                                <input type="number" name="ref_mobile_no" id="ref_mobile_no"
                                                       class="form-control  ref_mobile_no " placeholder="Ref Mobile Number"
                                                >
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> Ref City </label>
                                                <input type="text" name="ref_city" id="ref_city"
                                                       class="form-control  ref_city " placeholder="Ref city"
                                                >
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> File No </label>
                                                <input type="number" name="file_no" id="file_no"
                                                       class="form-control  file_no required" placeholder="File number"
                                                       required  >
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div>
                                                    <label> Select Finance </label>
                                                    <select class="form-select" name="finances_master_id"
                                                            aria-label="Default select example " required>
                                                        <option selected value=""> Select finance</option>
                                                        @foreach ($financeMasters as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @endforeach
                                                        @error('finances_master_id')
                                                        <p style="color: red;">{{ $message }}</p>
                                                        @enderror
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> Processing Fee </label>
                                                <input type="number" name="Processing" id="Processing"
                                                       class="form-control  Processingfee required" placeholder="--Processing Fee--"
                                                       required >
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> Security Charge </label>
                                                <input type="number" name="mobile_security_charges" id="mobile_security_charges"
                                                       class="form-control required" placeholder="--Security Charge--"
                                                       required >
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> Per Month EMI Charge </label>
                                                <input type="number" name="EMICharge" id="EMICharge"
                                                       class="form-control emicharge required" placeholder="--Per Month EMI Charge--"
                                                       value='0' required >
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> Down Payment </label>
                                                <input type="number" name="DownPayment" id="DownPayment"
                                                       class="form-control DownPayment required" placeholder="--Down Payment--"
                                                       required >
                                            </div>
                                        </div>
                                        <div class="col-md-4">

                                            <div class="form-group">
                                                <label> Payable Amount </label>
                                                <input type="number" name="FinanceAmount" id="FinanceAmount"
                                                       class="form-control FinanceAmount required" placeholder="--Payable Amount--"
                                                       required >
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> Month Duration </label>
                                                <input type="number" name="MonthDuration" id="MonthDuration"
                                                       class="form-control MonthDuration required" placeholder="--Month Duration--"
                                                       required >
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> Deduction Date </label>
                                                <select class="form-control  required" name="DeductionDate"
                                                        id="DeductionDate" required>
                                                    @foreach ([1, 5, 10, 15, 20] as $day)
                                                        <option value="{{ $day }}">{{ $day }}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> Penalty Charges </label>
                                                <input type="number" name="Penalty" id="Penalty"
                                                       class="form-control Penalty required" placeholder="--Penalty Charges--" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> Per month value </label>
                                                <input type="number" name="permonthvalue" id=""
                                                       class="form-control  required" placeholder="--Per month value--" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"><br><br>
                                                <h6>
                                                    <div id="permonth" style="color: red;">
                                                    </div>
                                                </h6>


                                                <input type="hidden" name="financ_year" id="financ_year" value=" {{ date('Y') }}">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="card-action">
                                        <button class="btn btn-success" type="submit">Submit
                                        </button>
                                        <a href="{{ route('admin.finance.index') }}" class="btn btn-danger">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-script')

    <script>

            $(document).on('change', '.imei_id', function () {
                updateIMEIDropdowns();
            });

            $('#name, #city').inputmask({
                regex: "^[a-zA-Z ]*$",
                placeholder: ''
            });

            $('input[name="ref_mobile_no"]').mask('0000000000');

            $('#saleForm').validate({
                rules: {
                    file_no:{
                        required: true
                    },

                    FinanceAmount:{
                        required:true
                    }
                },
                messages: {
                    customer: {
                        required: "Please Select a Customer",
                    },
                    FinanceAmount: {
                        required: "Please enter a invoice number",
                    },
                    invoice_date: {
                        required: "Please select date"
                    },
                    file_no:{
                        required: "Please enter a file Number",
                    },
                    sub_total: {
                        required: "Please enter sub type"
                    },
                    tax_type: {
                        required: "please enter tax type"
                    },
                    tax: {
                        required: "please enter tax"
                    },
                    tax_amount: {
                        required: "please enter tax amount"
                    },
                    total_amount: {
                        required: "please enter total amount"
                    },
                    payment_method: {
                        required: "please select Payment mode"
                    },
                    discount: {
                        required: "please enter discount"
                    },
                    discount_amount: {
                        required: "please enter discount amount"
                    },
                    price: {
                        required: "please enter price"
                    },
                    product_name: {
                        required: "please select Product Name"
                    },
                    brand_name: {
                        required: "please select Brand Name"
                    },
                    amount:{
                        required: "please enter amount"
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
