
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
                        <a href="{{ route('admin.sale.index') }}">Sale</a>
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
                        <form method="POST" action="{{ route('admin.sale.update', [$data->id , optional($deduction)->finance_id]) }}" id="saleForm">
                            @csrf
                            @method('PUT')

                            @if(Session::has('error'))
                                <div class="alert alert-danger text-danger " id="error-alert">
                                    {{Session::get('error')}}
                                </div>
                            @endif
                                <input type="hidden" name="finance_id" value="{{ optional($deduction)->finance_id }}">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Customer<span style="color: red">*</span></label>
                                            <select class="form-select customer" aria-label="Default select example"
                                                    name="customer_id">
                                                <option selected> Select Customer</option>
                                                @foreach ($customers as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ $selectedCustomer == $item->id ? 'selected' : '' }}>
                                                        {{ $item->name }}</option>
                                                @endforeach
                                                @error('customer')
                                                <p style="color: red;">{{ $message }}</p>
                                                @enderror
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div>
                                                <label for="">Invoice No<span style="color: red">*</span></label>
                                                <input type="text" class="form-control" name="invoice_no" id="invoice_no"
                                                       value="{{ $data->invoice_no }}" readonly required />
                                                @error('invoice_no')
                                                <p style="color: red;">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div>
                                                <label for="">Invoice Date<span style="color: red">*</span></label>
                                                <input id="datepicker" name="invoice_date" class="form-control datepicker"
                                                       placeholder="Select Date" onchange="GetOrderNo()"
                                                       value="{{ $data->invoice_date }}" />
                                                @error('invoice_date')
                                                <p style="color: red;">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <table class="display table table-striped table-hover">
                                            <thead>

                                            <tr >
                                                <th scope="col">sr no</th>
                                                <th scope="col">Brand</th>
                                                <th scope="col">Product</th>
                                                <th scope="col">IMI No</th>
                                                {{--                                                <th scope="col">Price</th> --}}
                                                <!--<th scope="col">Discount</th>
                                                {{--                                                <th scope="col">Discount Amount</th> --}}-->
                                                <th scope="col">Sub Total</th>
                                                <th scope="col">Tax</th>
                                                <th scope="col">Tax Amount</th>
                                                <th scope="col">Total</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody class="table-group-divider" id="add-table-row">
                                            @php
                                                $i = 0;
                                            @endphp
                                            @foreach($data1 as $index => $item)
                                                <tr class="product-row">
                                                    <th scope="row" class="row-index text-center index">{{ $i++ }}</th>
                                                    <td>

                                                        <select name="brand_id[]"  class="form-control brand-name">
                                                            @foreach($brands as $brand)
                                                                <option value="{{ $brand->id }}" {{ isset($item->product->brand_id) && $item->product->brand_id == $brand->id ? 'selected' : '' }}>
                                                                    {{ $brand->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                    </td>
                                                    <td>

                                                        <select name="products[{{ $index }}][product_id]"  class="form-control product-name">
                                                            @foreach($products as $product)
                                                                <option value="{{ $product->id }}" {{ isset($item->product_id) && $item->product_id == $product->id ? 'selected' : '' }}>
                                                                    {{ $product->product_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('product_id')
                                                        <p style="color: red;">{{ $message }}</p>
                                                        @enderror
                                                    </td>

                                                    <td>
                                                        <select name="products[{{ $index }}][imei_id]"  class="form-control imei_id">
                                                            @foreach($imiNumbers as $imi)
                                                                <option value="{{ $imi->id }}" {{ isset($item->imei_id) && $item->imei_id == $imi->id ? 'selected' : '' }}>
                                                                    {{ $imi->imei }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('imei_id')
                                                        <p style="color: red;">{{ $message }}</p>
                                                        @enderror
                                                    </td>


                                                    <input type="hidden" class="form-control price-select price"
                                                           name="products[{{$index}}][price]" id="price"
                                                           value="{{ $item->price }}" readonly required />
                                                    @error('price')
                                                    <p style="color: red;">{{ $message }}</p>
                                                    @enderror


                                                    <!--<td>
                                                        <div class="input-group">
                                                        <input type="text" class="form-control discount"
                                                               name="products[{{$index}}][discount]" id="discount"
                                                               value="{{ $item->discount }}" required />
                                                        <span class="input-group-text" id="basic-addon2">%</span>
                                                        </div>
                                                        @error('discount')
                                                        <p style="color: red;">{{ $message }}</p>
                                                        @enderror
                                                    </td>


                                                    <input type="hidden"
                                                           class="form-control discountAmount discount_amount"
                                                           name="products[{{$index}}][discount_amount]"
                                                           value="{{ $item->discount_amount }}" id="discountAmount"
                                                           readonly required />
                                                    @error('discount_amount')
                                                    <p style="color: red;">{{ $message }}</p>
                                                    @enderror
                                                    -->

                                                    <td>
                                                        <div class="input-group">
                                                        <span class="input-group-text" id="basic-addon2">₹</span>
                                                        <input type="number" class="form-control priceSubTotal"
                                                               id="priceSubTotal" name="products[{{$index}}][price_subtotal]"
                                                               value="{{ $item->price_subtotal }}" readonly required />
                                                        </div>
                                                        @error('price_subtotal')
                                                        <p style="color: red;">{{ $message }}</p>
                                                        @enderror
                                                    </td>

                                                    <td>
                                                        <div class="input-group">
                                                        <input type="number" class="form-control tax" id="tax"
                                                               name="products[{{$index}}][tax]" value="{{ $item->tax }}"
                                                               required />
                                                            <span class="input-group-text" id="basic-addon2">%</span>
                                                        </div>
                                                        @error('tax')
                                                        <p style="color: red;">{{ $message }}</p>
                                                        @enderror
                                                    </td>

                                                    <td>
                                                        <div class="input-group">
                                                            <span class="input-group-text" id="basic-addon2">₹</span>
                                                        <input type="number" class="form-control taxAmount tax-amount"
                                                               name="products[{{$index}}][tax_amount]" id="tax_amount"
                                                               value="{{ $item->tax_amount }}" readonly required />
                                                        </div>
                                                        @error('tax_amount')
                                                        <p style="color: red;">{{ $message }}</p>
                                                        @enderror
                                                    </td>

                                                    <td>
                                                        <div class="input-group">
                                                            <span class="input-group-text" id="basic-addon2">₹</span>
                                                        <input type="number"
                                                               class="form-control total total-amount totalAmount"
                                                               id="totalAmount" name="products[{{$index}}][price_total]"
                                                               value="{{ $item->price_total }}" required />
                                                        </div>
                                                        @error('total')
                                                        <p style="color: red;">{{ $message }}</p>
                                                        @enderror
                                                    </td>

                                                    <td class="d-inline-flex gap-1">
                                                        <button type="button" class="btn btn-success add-row">+</button>
                                                        <button type="button"
                                                                class="btn btn-danger remove-row">-</button>
                                                        <button type="button" class="btn btn-secondary duplicate-row">
                                                            <i class="fas fa-copy"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div>
                                                <label for="">Sub Total<span style="color: red"
                                                                             id="grandTotal">*</span></label>
                                                <input type="number" class="form-control subTotal"
                                                       name="products[0][sub_total]" placeholder="sub total"
                                                       value="{{ $data->sub_total }}" readonly required />
                                                @error('sub_total')
                                                <p style="color: red;">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div>
                                                <label for="">Tax Type<span style="color: red">*</span></label>
                                                <select class="form-control form-select taxType" name="tax_type"
                                                        aria-label="Default select example" required>
                                                    <option selected>Select Tax Type</option>
                                                    <option value="1" {{ $data->tax_type == 1 ? 'selected' : '' }}>
                                                        CGST/SGST</option>
                                                    <option value="2" {{ $data->tax_type == 2 ? 'selected' : '' }}>
                                                        IGST</option>
                                                </select>

                                                @error('tax_type')
                                                <p style="color: red;">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div>
                                                <label for=""> Total Tax Amount<span
                                                        style="color: red">*</span></label>
                                                <input type="number" class="form-control totalTaxAmount"
                                                       name="total_tax_amount" id="total_tax_amount"
                                                       placeholder="Enter Total Tax Amount"
                                                       value="{{ $data->total_tax_amount }}" readonly required />
                                                @error('tax_amount')
                                                <p style="color: red;">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

{{--                                    <div class="col-md-4">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <div>--}}
{{--                                                <label for="">Total Amount<span--}}
{{--                                                        style="color: red">*</span></label>--}}
{{--                                                <input type="number" class="form-control finalTotalAmount"--}}
{{--                                                       name="total_amount" id="total_amount"--}}
{{--                                                       placeholder="Enter Total  Amount" value="{{ $data->total_amount }}"--}}
{{--                                                       readonly required />--}}
{{--                                                @error('total_amount')--}}
{{--                                                <p style="color: red;">{{ $message }}</p>--}}
{{--                                                @enderror--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-md-4">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label for="">Rounded Amount<span style="color: red">*</span></label>--}}
{{--                                            <input type="number" class="form-control finalTotalAmount round_diff"--}}
{{--                                                   name="total_amount" id="total_amount" placeholder="Enter Total  Amount"--}}
{{--                                                   value="{{ $data->total_amount_rounded }}"--}}
{{--                                                   readonly required />--}}
{{--                                            @error('total_amount')--}}
{{--                                            <p style="color: red;">{{ $message }}</p>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    <div class="col-md-4">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label for="">Total Amount<span style="color: red">*</span></label>--}}
{{--                                            <input type="number" class="form-control total_amount_rounded"--}}
{{--                                                   name="total_amount_rounded" id="total_amount_rounded" placeholder="Enter Total  Amount rounded" value="{{ $data->total }}"--}}
{{--                                                   readonly required />--}}
{{--                                            @error('total_amount_rounded')--}}
{{--                                            <p style="color: red;">{{ $message }}</p>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Rounded Amount<span style="color: red">*</span></label>
                                            <input type="number" class="form-control finalTotalAmount round_diff"
                                                   name="total_amount_rounded" id="total_amount" placeholder="Enter Total  Amount"
                                                   value="{{$data->total_amount_rounded}}"
                                                   readonly required />
                                            @error('total_amount')
                                            <p style="color: red;">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Total Amount <span style="color: red">*</span></label>
                                            <input type="number" class="form-control total_amount_rounded"
                                                   name="total_amount" id="total_amount_rounded" placeholder="Enter Total  Amount rounded"
                                                   value="{{$data->total_amount}}"
                                                   readonly required />
                                            @error('total_amount_rounded')
                                            <p style="color: red;">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div>
                                                <label for="paymentMethod">Payment Method<span
                                                        style="color: red">*</span></label>
                                                <select class="form-select paymentMethod"
                                                        aria-label="Default select example" name="payment_method"
                                                        id="paymentMethod">
                                                    <option selected> Select Payment Method</option>
                                                    <option value="1"
                                                        {{ $data->payment_method == 1 ? 'selected' : '' }}>Online/Cash</option>
                                                    <option value="2"
                                                        {{ $data->payment_method == 2 ? 'selected' : '' }}>Finance</option>
                                                </select>
                                                @error('payment_method')
                                                <p style="color: red;">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <h4>Payment</h4>
                                        <table class="display table table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th>Mode</th>
                                                <th>Amount</th>
                                                <th>Reference</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($salestransc as $st)
                                                <tr>
                                                    <td>
                                                        <select name="payment[0][payment_mode]" id="mode"
                                                                class="form-control">
                                                            <option {{ $st->payment_mode == '1' ? 'selected' : '' }}value="1">Cash</option>
                                                            <option {{ $st->payment_mode == '2' ? 'selected' : '' }} value="2">Online</option>
{{--                                                            <option {{ $st->payment_mode == '3' ? 'selected' : '' }} value="3">Finance</option>--}}
                                                        </select>
                                                    </td>
                                                    <td><input type="text" name="payment[0][amount]" id="amount"
                                                               value="{{ $st->amount ?? ''}}" class="form-control amount">
                                                    </td>
                                                    <td><input type="text" name="payment[0][reference_no]"
                                                               id="reference_no" value="{{ $st->reference_no ?? '' }}"
                                                               class="form-control reference_no "></td>
                                                    <td>
                                                        <button type="button" class="btn btn-success add-payment-row">+
                                                        </button>
                                                        <button type="button" class="btn btn-danger remove-payment-row">-
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    @if($selectfinance)
                                        <div class="row mt-3 financeDetail" id="finance_detail">
                                            <h4>Finance Details</h4>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label> Ref Name </label>
                                                    <input type="text"  name="finance[ref_name]" id="ref_name"
                                                           class="form-control  ref_name "  value="{{ $selectfinance->ref_name ?? '' }}" placeholder="Ref Name">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label> Ref Mobile Number </label>
                                                    <input type="number" name="finance[ref_mobile_no]" id="ref_mobile_no"
                                                           class="form-control  ref_mobile_no " value="{{ $selectfinance->ref_mobile_no ?? '' }}" placeholder="Ref Mobile Number"
                                                    >
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label> Ref City </label>
                                                    <input type="text" name="finance[ref_city]" id="ref_city"
                                                           class="form-control  ref_city " placeholder="Ref city"
                                                           value="{{ $selectfinance->ref_city ?? '' }}"
                                                    >
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label> File No </label>
                                                    <input type="number" name="finance[file_no]" id="file_no"
                                                           class="form-control  file_no required" value="{{ $selectfinance->file_no ?? '' }}" placeholder="File number"
                                                    >
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Select Finance</label>
                                                    <select class="select2 form-control required" name="finance[finances_master_id]">
                                                        @foreach ($financeMaster as $item)
                                                            <option value="{{ $item->id }}" {{ $item->id == $selectfinanceID ? 'selected' : '' }}>
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
                                                    <input type="number" name="finance[processing_fee]" id="Processing"
                                                           class="form-control required" placeholder="--Processing Fee--"
                                                           value="{{ $selectfinance->processing_fee ?? '' }}" onkeyup="SetFinanceAmount()">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label> Security Charge </label>
                                                    <input type="number" name="finance[mobile_security_charges]" id="mobile_security_charges"
                                                           class="form-control required" placeholder="--Security Charge--"
                                                           value="{{ $selectfinance->mobile_security_charges ?? '' }}"
                                                           onkeyup="SetFinanceAmount()" ;>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Per Month EMI Charge</label>
                                                    <input type="number" name="finance[emi_charger]" id="EMICharge"
                                                           class="form-control required" placeholder="--EMI Charge--"
                                                           value="{{ $selectfinance->emi_charger ?? '' }}" onkeyup="SetMonthDuration()">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Down Payment</label>
                                                    <input type="number" name="finance[downpayment]" id="DownPayment"
                                                           class="form-control required" placeholder="--Down Payment--"
                                                           value="{{ $selectfinance->downpayment ?? '' }}" onkeyup="SetFinanceAmount()">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Payable Amount</label>
                                                    <input type="number" name="finance[finance_amount]" id="FinanceAmount"
                                                           class="form-control required" placeholder="--Payable Amount--"
                                                           value="{{ $selectfinance->finance_amount ?? '' }}" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Month Duration</label>
                                                    <input type="number" name="finance[month_duration]" id="MonthDuration"
                                                           class="form-control required" placeholder="--Month Duration--"
                                                           value="{{ $selectfinance->month_duration ?? '' }}" onkeyup="SetMonthDuration()">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Deduction Date</label>
                                                    <select class="form-control required" name="finance[deduction_date]" id="DeductionDate">
{{--                                                        @for ($i = 1; $i <= 28; $i++)--}}
{{--                                                            <option value="{{ $i }}" {{ $i == $selectfinance->deduction_date ? 'selected' : '' }}>--}}
{{--                                                                {{ $i }}--}}
{{--                                                            </option>--}}
{{--                                                        @endfor--}}
                                                        @foreach ([1, 5, 10, 15, 20] as $i)
                                                            <option value="{{ $i }}" {{ $i == $selectfinance->deduction_date ? 'selected' : '' }}>
                                                                {{ $i }}
                                                            </option>
                                                        @endforeach



                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Penalty Charges</label>
                                                    <input type="number" name="finance[penalty]" id="Penalty"
                                                           class="form-control required" placeholder="--Penalty Charges--"
                                                           value="{{ $selectfinance->penalty ?? '' }}">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group"><br><br>
                                                    <h6><div id="permonth" style="color: red;"></div></h6>
                                                    <input type="hidden" name="finance[emi_value]" id="permonthvalue"
                                                           value="{{ $selectfinance->emi_value ?? '' }}">
                                                    <input type="hidden" name="finance[finance_year]" id="finance_year" value="">
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="card-action">
                                        <button class="btn btn-success" type="submit">Submit</button>
                                        <a href="{{ route('admin.sale.index') }}" class="btn btn-danger">Cancel</a>
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

        // document.addEventListener('DOMContentLoaded', function () {
        //     const paymentMethod = document.getElementById('paymentMethod');
        //     const financeDetails = document.getElementById('finance_detail');
        //
        //     function toggleFinanceDetails() {
        //         financeDetails.style.display = paymentMethod.value === '2' ? 'block' : 'none';
        //     }
        //
        //     paymentMethod.addEventListener('change', toggleFinanceDetails);
        //     window.onload = toggleFinanceDetails;
        // });

        $(document).ready(function() {
            setTimeout(function() {
                $('#error-alert').fadeOut('slow');
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
                $('#add-table-row tr').each(function(index, row) {
                    $(row).find('input, select').each(function() {
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

            function calculateRow($row) {
                const price = parseFloat($row.find('.price').val()) || 0;
                const total_Amount = parseFloat($row.find('.totalAmount').val()) || 0;
                const discount = parseFloat($row.find('.discount').val()) || 0;
                const tax = parseFloat($row.find('.tax').val()) || 0;

                const discountAmount = (price * discount) / 100;
                const subtotal = price - discountAmount;
                const taxAmount =   total_Amount - (total_Amount / (1+(tax / 100)));
                const total = subtotal + taxAmount;


                $row.find('.discountAmount').val(discountAmount.toFixed(2));
                $row.find('.priceSubTotal').val(subtotal.toFixed(2));
                $row.find('.taxAmount').val(taxAmount.toFixed(2));
                $row.find('.totalAmount').val(total.toFixed(2));
            }

            function calculateGrandTotal() {
                let subtotalSum = 0, taxSum = 0, totalSum = 0;

                $('#add-table-row tr').each(function () {
                    subtotalSum += parseFloat($(this).find('.priceSubTotal').val()) || 0;
                    taxSum += parseFloat($(this).find('.taxAmount').val()) || 0;
                    totalSum += parseFloat($(this).find('.totalAmount').val()) || 0;
                });

                $('.subTotal').val(subtotalSum.toFixed(2));
                $('.totalTaxAmount').val(taxSum.toFixed(2));
                $('.finalTotalAmount').val(totalSum.toFixed(2));

                const roundedTotal = (totalSum - Math.floor(totalSum) >= 0.5)
                    ? Math.ceil(totalSum)
                    : Math.floor(totalSum);

                $('.total_amount_rounded').val(roundedTotal.toFixed(2));
                const diff = roundedTotal - totalSum;
                $('.round_diff').val(diff.toFixed(2));
            }

            // Input event: price, discount, tax
            $(document).on('input', '.price, .discount, .tax', function () {
                const $row = $(this).closest('tr');
                calculateRow($row);
                calculateGrandTotal();
            });

            // When total amount (only) changes
            $(document).on('input', '.totalAmount', function () {
                calculateGrandTotal();
            });
            // Add new row
            $(document).on('click', '.add-row', function() {

                let $row = $(this).closest('tr');
                let $newRow = $row.clone();

                // Clear all input/select values
                $newRow.find('input').val('');
                $newRow.find('select').val('');
                $newRow.find('.tax').val('18');

                // Append cloned row
                $('#add-table-row').append($newRow);

                resetRowIndexes();
                resetActionButtons();// 🔥 Very important
            });


            // Remove row
            $(document).on('click', '.remove-row', function () {
                if ($('#add-table-row tr').length > 1) {
                    $(this).closest('tr').remove();
                    resetRowIndexes();
                    resetActionButtons();
                    calculateGrandTotal();
                }
            });

            // Duplicate row
            $(document).on('click', '.duplicate-row', function () {
                const $row = $(this).closest('tr');
                const $dupRow = $row.clone();

                // Preserve selects (brand, product, IMEI) if needed
                const selectedBrand = $row.find('.brand-select').val();
                const selectedProduct = $row.find('.product-select').val();
                const selectedIMEI = $row.find('.imei_id').val();

                $dupRow.find('input').val(''); // clear inputs

                $dupRow.find('.brand-select').val(selectedBrand);
                $dupRow.find('.product-select').val(selectedProduct);
                $dupRow.find('.imei_id').val(selectedIMEI);
                $dupRow.find('.tax').val('18');
                $dupRow.find('.remove-row').removeClass('d-none');

                $('#add-table-row').append($dupRow);
                resetActionButtons();
                calculateGrandTotal();
                resetRowIndexes();

            });

            // On page load, calculate totals
            calculateGrandTotal();
            resetActionButtons();
        });


        function GetOrderNo() {
            var selectDate = $("#invoice_date").val();
            $.ajax({
                url: 'get_order_no',
                type: 'POST',
                data: {
                    selectDate: selectDate,
                    Flag: "Find Finance No"
                },
                success: function(data) {
                    data = data.split(',');
                    $("#bill_no").val(data[0]);
                    $("#financ_year").val(data[1]);
                    $("#InvoiceNo").val(data[2]);
                }
            });
        }

        //
        // function SetFinanceAmount() {
        //     var DownPayment = $("#DownPayment").val();
        //     var Processing = $("#Processing").val();
        //     var EMICharge = $("#EMICharge").val();
        //     var Price = $("#total_amount").val();
        //     var MonthDuration = $("#MonthDuration").val();
        //     var SecurityDeposit = $("#mobile_security_charges").val();
        //     total = (parseInt(Price) + parseInt(Processing) + parseInt(SecurityDeposit)) - DownPayment;
        //
        //     $("#FinanceAmount").val(total);
        //     $("#MonthDuration").val("");
        //     $("#permonth").html("");
        // }

        function SetFinanceAmount() {
            var DownPayment = parseFloat($("#DownPayment").val()) || 0;
            var Processing = parseFloat($("#Processing").val()) || 0;
            var EMICharge = parseFloat($("#EMICharge").val()) || 0; // unused?
            var Price = parseFloat($("#total_amount_rounded").val()) || 0;
            var MonthDuration = parseFloat($("#MonthDuration").val()) || 0; // optional
            var SecurityDeposit = parseFloat($("#mobile_security_charges").val()) || 0;
            total = (parseInt(Price) + parseInt(Processing) + parseInt(SecurityDeposit)) - DownPayment;
            $("#FinanceAmount").val(total.toFixed(2));
            // Optional: Clear per month and duration only when total is zero
            if (total === 0) {
                $("#MonthDuration").val("");
                $("#permonth").html("");
            }
        }
        $('#DownPayment, #Processing, #total_amount, #mobile_security_charges,#EMICharge,#MonthDuration').on('change input', SetFinanceAmount);


        function PriceAmount() {
            var DownPayment = $("#DownPayment").val();
            var FinanceAmount = $("#FinanceAmount").val();
            var MonthDuration = $("#MonthDuration").val();
            $("#DownPayment").val("");
            $("#FinanceAmount").val("");
            $("#MonthDuration").val("");
        }

        function SetMonthDuration() {
            var FinanceAmount = $("#FinanceAmount").val();
            var MonthDuration = $("#MonthDuration").val();
            var EMICharge = $("#EMICharge").val();
            var Price = $("#total_amount_rounded").val();
            var Processing = $("#Processing").val();
            var DownPayment = $("#DownPayment").val();
            var SecurityDeposit = $("#mobile_security_charges").val();

            if (EMICharge == "") {
                EMICharge = '0';
            } else {
                EMICharge = EMICharge;
            }
            var ChargeTotal = parseInt(EMICharge) * parseInt(MonthDuration);
            Financetotal = (parseInt(Price) + parseInt(Processing) + parseInt(SecurityDeposit)) - DownPayment;
            AmountTotal = parseInt(Financetotal) + parseInt(ChargeTotal);

            $("#FinanceAmount").val(Math.round(AmountTotal));
            var total = AmountTotal / MonthDuration;
            $("#permonthvalue").val(Math.round(total));
            $("#permonth").html("EMI Per Month : " + Math.round(total));
        }

        $('#datepicker').datepicker({
            uiLibrary: 'bootstrap5',
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
            orientation: 'bottom',
            startDate: new Date()

        });

        $(document).on('change', '.product-select', function() {
            var productId = $(this).val();
            var $row = $(this).closest('tr');
            if (productId) {
                $.ajax({
                    url: "{{ route('admin.product.getPrice', ':productId') }}".replace(':productId',
                        productId),
                    type: 'GET',
                    success: function(data) {
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

        $(document).ready(function() {
            $(document).on('click', '.add-payment-row', function() {
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

            $(document).on('click', '.remove-payment-row', function() {
                const totalRows = $(this).closest('tbody').find('tr').length;
                if (totalRows > 1) {
                    $(this).closest('tr').remove();
                } else {
                    alert('At least one payment entry is required.');
                }
            });
        });

        $(document).ready(function() {
            // Initially hide the finance detail section
            $('#finance_detail').hide();
            $('#online_detail').hide();
            // Listen to changes on the payment method dropdown
            $('.paymentMethod').change(function() {
                var selected = $(this).val();
                if (selected == '2') { // 2 = Finance
                    $('#finance_detail').show();
                } else {
                    $('#finance_detail').hide();
                    // Optional: clear all inputs in the finance detail section when hidden
                    $('#finance_detail').find('input, select').val('');
                    $('#permonth').text('');
                    $('#permonthvalue').val('');
                }
            });

            $(document).ready(function () {
                function toggleDetails() {
                    var selected = $('#paymentMethod').val();

                    if (selected === '2') {
                        $('#finance_detail').show();
                        $('#online_detail').hide();
                    } else {
                        $('#finance_detail').hide().find('input, select').val('');
                        $('#permonth').text('');
                        $('#permonthvalue').val('');
                        $('#online_detail').show();
                    }
                }
                toggleDetails();
               $('#paymentMethod').on('load', toggleDetails);
            });

            $(document).on('change', '.brand-select', function() {
                let brandId = $(this).val();
                let $productSelect = $(this).closest('tr').find('.product-select');

                $productSelect.html('<option value="">Loading...</option>');

                if (brandId) {
                    $.ajax({
                        url: "{{ route('admin.product.getproducts', ':brand_id') }}"
                            .replace(
                                ':brand_id', brandId),
                        type: 'GET',
                        success: function(response) {
                            $productSelect.empty().append(
                                '<option value="">Select Product</option>');
                            $.each(response, function(index, product) {
                                $productSelect.append('<option value="' +
                                    product.id +
                                    '">' + product.product_name +
                                    '</option>');
                            });
                        },
                        error: function() {
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
                $('.imei_id').each(function() {
                    let selected = $(this).val();
                    if (selected) {
                        selectedIMEIs.push(selected);
                    }
                });

                $('.imei_id').each(function() {
                    let currentVal = $(this).val();
                    $(this).find('option').each(function() {
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
                    customer_id: {
                        required: true,
                    },
                    invoice_no: {
                        required: true,
                    },
                    invoice_date: {
                        required: true
                    },
                    file_no:{
                        required: true
                    },
                    sub_total: {
                        required: true
                    },
                    tax_type: {
                        required: true
                    },
                    tax: {
                        required: true
                    },
                    tax_amount: {
                        required: true
                    },
                    total_amount: {
                        required: true
                    },
                    payment_method: {
                        required: true
                    },
                    discount: {
                        required: true
                    },
                    discount_amount: {
                        required: true
                    },
                    price: {
                        required: true
                    },

                    brand_name: {
                        required: true
                    },
                    product_name: {
                        required: true
                    },
                    amount:{
                        required:true
                    }
                },
                messages: {
                    customer: {
                        required: "Please Select a Customer",
                    },
                    invoice_no: {
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
