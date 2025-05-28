
@extends('layouts.app')
{{-- @if (Auth::guard('admin')->check()) --}}
@section('title', 'Admin Panel')

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
                        <a href="#">Edit Finance</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Edit Finance</div>
                        </div>
                        <form method="POST" action="{{ route('admin.finance.update', [$finances->id, $deduction->finance_id]) }}" id="saleForm">
                            @csrf
                            @method('PUT')
                            @if(Session::has('error'))
                                <div class="alert alert-danger text-danger " id="error-alert-finance">
                                    {{Session::get('error')}}
                                </div>
                            @endif
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Customer<span style="color: red">*</span></label>

                                            <select class="form-select customer" aria-label="Default select example"
                                                    name="customer_id" required>
                                                <option selected> Select Customer</option>

                                                @foreach ($customers as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{  $item->id == $finances->customer_id? 'selected' : '' }}>
                                                        {{ $item->name }}</option>
                                                @endforeach
                                                @error('customer')
                                                <p style="color: red;">{{ $message }}</p>
                                                @enderror
                                            </select>

                                        </div>
                                    </div>
                                        <div class="row mt-3 financeDetail" id="finance_detail">
                                            <h4>Finance Details</h4>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label> Ref Name </label>
                                                    <input type="text"  name="ref_name" id="ref_name"
                                                           class="form-control  ref_name "  value="{{ $finances->ref_name ?? '' }}" placeholder="Ref Name" >
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label> Ref Mobile Number </label>
                                                    <input type="number" name="ref_mobile_no" id="ref_mobile_no"
                                                           class="form-control  ref_mobile_no " value="{{ $finances->ref_mobile_no ?? '' }}" placeholder="Ref Mobile Number"
                                                    >
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label> Ref City </label>
                                                    <input type="text" name="ref_city" id="ref_city"
                                                           class="form-control  ref_city " placeholder="Ref city"
                                                           value="{{ $finances->ref_city ?? '' }}"
                                                    >
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label> File No </label>
                                                    <input type="number" name="file_no" id="file_no"
                                                           class="form-control  file_no required" value="{{ $finances->file_no ?? '' }}" placeholder="File number"
                                                           required  >
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Select Finance</label>
                                                    <select class="select2 form-control required" name="finances_master_id" required>
                                                        @foreach ($financeMaster as $item)
                                                            <option value="{{ $item->id }}" {{ $item->id == $finances->finances_master_id ? 'selected' : '' }}
                                                            >
                                                                {{ $item->name }}
                                                            </option>

                                                        @endforeach
                                                    </select>
                                                    @error('finance.finances_master_id')
                                                    <p style="color: red;">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Processing Fee</label>
                                                    <input type="number" name="processing_fee" id="Processing"
                                                           class="form-control required" placeholder="--Processing Fee--"
                                                           value="{{ $finances->processing_fee ?? '' }}" required >
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label> Security Charge </label>
                                                    <input type="number" name="mobile_security_charges" id="mobile_security_charges"
                                                           class="form-control required" placeholder="--Security Charge--"
                                                           value="{{ $finances->mobile_security_charges ?? '' }}"
                                                           required >
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Per Month EMI Charge</label>
                                                    <input type="number" name="emi_charger" id="EMICharge"
                                                           class="form-control required" placeholder="--EMI Charge--"
                                                           value="{{ $finances->emi_charger ?? '' }}" required>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Down Payment</label>
                                                    <input type="number" name="downpayment" id="DownPayment"
                                                           class="form-control required" placeholder="--Down Payment--"
                                                           value="{{ $finances->downpayment ?? '' }}" required >
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Payable Amount</label>
                                                    <input type="number" name="finance_amount" id="FinanceAmount"
                                                           class="form-control required" placeholder="--Payable Amount--"
                                                           value="{{ $finances->finance_amount ?? '' }}" required >
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Month Duration</label>
                                                    <input type="number" name="month_duration" id="MonthDuration"
                                                           class="form-control required" placeholder="--Month Duration--"
                                                           value="{{ $finances->month_duration ?? '' }}" required >
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Deduction Date</label>
                                                    <select class="form-control required" name="deduction_date" id="DeductionDate" required>
                                                        @foreach ([1, 5, 10, 15, 20] as $i)
                                                            <option {{$finances->dedication_date ==  $i   ? 'selected' : '' }} value="{{ $i }}" >
                                                                {{ $i }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Penalty Charges</label>
                                                    <input type="number" name="penalty" id="Penalty"
                                                           class="form-control required" placeholder="--Penalty Charges--"
                                                           value="{{ $finances->penalty ?? '' }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label> Per month Amount </label>
                                                    <input type="number" name="permonthvalue" id="permonthvalue"
                                                           class="form-control permonthvalue required" placeholder="--Per month value--"  value="{{ $finances->emi_value ?? '' }}" required>
                                                </div>
                                            </div>
{{--                                            <div class="col-md-4">--}}
{{--                                                <div class="form-group"><br><br>--}}
{{--                                                    <h6><div id="permonth" style="color: red;"></div></h6>--}}
{{--                                                    <input type="hidden" name="emi_value" id="permonthvalue"--}}
{{--                                                           value="{{ $finances->emi_value ?? '' }}">--}}
{{--                                                    <input type="hidden" name="finance_year" id="finance_year" value="">--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
                                        </div>
                                    <div class="card-action">
                                        <button class="btn btn-success" type="submit">Submit</button>
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
        $(document).ready(function() {
            setTimeout(function() {
                $('#error-alert-finance').fadeOut('slow');
            }, 5000);
        });


        document.addEventListener('DOMContentLoaded', function () {
            const paymentMethod = document.getElementById('paymentMethod');
            const financeDetails = document.getElementById('finance_detail');
            const onlineDetails = document.getElementById('online_detail');

            function toggleDetails() {
                const selected = paymentMethod.value;
                if (selected === '2') {
                    financeDetails.style.display = 'flex';
                    if (onlineDetails) onlineDetails.style.display = 'none';
                } else {
                    financeDetails.style.display = 'none';
                    if (financeDetails) {
                        financeDetails.querySelectorAll('input, select').forEach(el => el.value = '');
                        document.getElementById('permonth').innerText = '';
                        document.getElementById('permonthvalue').value = '';
                    }
                }
            }

            // Initial run on page load
            toggleDetails();

            // On change
            paymentMethod.addEventListener('change', toggleDetails);
        });

        $(document).ready(function () {
            function resetActionButtons() {
                const $rows = $('#add-table-row tr');
                if ($rows.length === 1) {
                    $rows.find('.remove-row').addClass('d-none');
                    $rows.find('.add-row').removeClass('d-none');
                } else {
                    $rows.each(function (index) {
                        const $row = $(this);
                        const isLast = index === $rows.length - 1;
                        $row.find('.remove-row').removeClass('d-none');
                        $row.find('.add-row').toggleClass('d-none', !isLast);
                    });
                }
            }

            function resetRowIndexes() {
                $('#add-table-row tr').each(function (index, row) {
                    $(row).find('input, select').each(function () {
                        let name = $(this).attr('name');
                        if (name) {
                            // Update number inside [ ]
                            name = name.replace(/\[\d+\]/, `[${index}]`);
                            $(this).attr('name', name);
                        }
                    });

                    // Update row number display (Sr No column)
                    $(row).find('.row-index').text(index + 1);
                });
            }

            $('#datepicker').datepicker({
                uiLibrary: 'bootstrap5',
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                orientation: 'bottom',
                startDate: new Date()

            });

            $(document).on('change', '.product-select', function () {
                var productId = $(this).val();
                var $row = $(this).closest('tr');
                if (productId) {
                    $.ajax({
                        url: "{{ route('admin.product.getPrice', ':productId') }}".replace(':productId',
                            productId),
                        type: 'GET',
                        success: function (data) {
                            $row.find('.price').val(data.mrp);
                            $row.find('.priceSubTotal').val(data.mrp);
                            $row.find('.totalAmount').val(data.mrp);
                            console.log(data.mrp);
                        }
                    });
                } else {
                    $('.price').val('');
                }
            });

            $(document).ready(function () {
                $(document).on('click', '.add-payment-row', function () {
                    // Count current rows to generate a unique index
                    let paymentIndex = $(this).closest('tbody').find('tr').length;

                    const newRow = `<tr>
            <td>
                <select name="payment[${paymentIndex}][payment_mode]" class="form-control">
                    <option value="1">Cash</option>
                    <option value="2">Online</option>
                    <option value="3">Finance</option>
                </select>
            </td>
            <td><input type="text" name="payment[${paymentIndex}][amount]" class="form-control"></td>
            <td><input type="text" name="payment[${paymentIndex}][reference_no]" class="form-control"></td>
            <td>
                <button type="button" class="btn btn-success add-payment-row">+</button>
                <button type="button" class="btn btn-danger remove-payment-row">-</button>
            </td>
        </tr>`;
                    $(this).closest('tbody').append(newRow);
                });

                $(document).on('click', '.remove-payment-row', function () {
                    const totalRows = $(this).closest('tbody').find('tr').length;
                    if (totalRows > 1) {
                        $(this).closest('tr').remove();
                    } else {
                        alert('At least one payment entry is required.');
                    }
                });
            });

            $(document).ready(function () {

                $(document).on('change', '.brand-select', function () {
                    let brandId = $(this).val();
                    let $productSelect = $(this).closest('tr').find('.product-select');

                    $productSelect.html('<option value="">Loading...</option>');

                    if (brandId) {
                        $.ajax({
                            url: "{{ route('admin.product.getproducts', ':brand_id') }}"
                                .replace(
                                    ':brand_id', brandId),
                            type: 'GET',
                            success: function (response) {
                                $productSelect.empty().append(
                                    '<option value="">Select Product</option>');
                                $.each(response, function (index, product) {
                                    $productSelect.append('<option value="' +
                                        product.id +
                                        '">' + product.product_name +
                                        '</option>');
                                });
                            },
                            error: function () {
                                $productSelect.html(
                                    '<option value="">Error loading products</option>');
                            }
                        });
                    } else {
                        $productSelect.html('<option value="">Select Product</option>');
                    }
                });

                function updateIMEIDropdowns() {
                    let selectedIMEIs = [];
                    $('.imei_id').each(function () {
                        let selected = $(this).val();
                        if (selected) {
                            selectedIMEIs.push(selected);
                        }
                    });

                    $('.imei_id').each(function () {
                        let currentVal = $(this).val();
                        $(this).find('option').each(function () {
                            let optionVal = $(this).val();
                            if (optionVal === "" || optionVal === currentVal) {
                                $(this).prop('disabled', false);
                            } else {
                                $(this).prop('disabled', selectedIMEIs.includes(optionVal));
                            }
                        });
                    });
                }

                $(document).on('change', '.product-select', function () {
                    let $row = $(this).closest('tr'); // Get current row
                    let productId = $(this).val();
                    let imeiDropdown = $row.find('.imei_id');
                    let urK = "{{ route('admin.sale.get-imeis', ':productId') }}".replace(':productId', productId);

                    if (productId) {
                        $.ajax({
                            url: urK,
                            method: 'GET',
                            success: function (data) {
                                imeiDropdown.empty().append('<option value="">Select IMEI No</option>');
                                $.each(data, function (id, imei) {
                                    imeiDropdown.append('<option value="' + id + '">' + imei + '</option>');
                                });

                                updateIMEIDropdowns();
                            },
                            error: function () {
                                alert('Could not fetch IMEI numbers.');
                            }
                        });
                    }
                });

                $(document).on('change', '.imei_id', function () {
                    updateIMEIDropdowns();
                });

                // $('.tax, .discount, .total,.amount,.reference_no , .Processingfee,.emicharge , .DownPayment,.FinanceAmount,.MonthDuration,.DeductionDate , .Penalty').inputmask({
                //     regex: "^[0-9.]*$",
                //     placeholder: ''
                // });

                $('input[name="ref_mobile_no"]').mask('0000000000');

                $('#name, #city').inputmask({
                    regex: "^[a-zA-Z ]*$",
                    placeholder: ''
                });

                $('#saleForm').validate({
                    rules: {

                        file_no: {
                            required: true
                        },

                    },
                    messages: {

                        file_no: {
                            required: "Please enter a file Number",
                        },

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
        });

    </script>
@endsection
