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
                        <a href="#">Add Sale</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Add Sale</div>
                        </div>
                        <form method="POST" action="{{ route('admin.sale.store') }}" id="saleForm">
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
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div>
                                                <label for="">Invoice No<span style="color: red">*</span></label>
                                                <input type="text" class="form-control" name="invoice_no" id="invoice_no"
                                                       value="{{ $invoiceNo }}" readonly required />
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
                                                       placeholder="Select Date" onchange="GetOrderNo()" />
                                                @error('invoice_date')
                                                <p style="color: red;">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <table class="display table table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th scope="col">sr no</th>
                                                <th scope="col">Brand</th>
                                                <th scope="col">Product</th>
                                                <th scope="col">IMEI No</th>
                                                {{-- <th scope="col">Price</th> --}}
                                                <th scope="col">Discount</th>
                                                {{-- <th scope="col">Discount Amount</th> --}}
                                                <th scope="col"> Sub Total</th>
                                                <th scope="col">Tax</th>
                                                <th scope="col">Tax Amount</th>
                                                <th scope="col">Total</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody class="table-group-divider" id="add-table-row">
                                            <tr>
                                                <th scope="row" class="row-index" style="text-align: center">1</th>
                                                <td>
                                                    <select
                                                        class="form-control form-select
                                                    brand-name brand-select"
                                                        name="products[0][brand_id]" aria-label="Default select example"
                                                        required>
                                                        <option value="">Select Brand</option>
                                                        @foreach ($brands as $brand)
                                                            <option value="{{ $brand->id }}"
                                                                {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                                                {{ $brand->name }}
                                                            </option>
                                                        @endforeach
                                                        @error('brand_id')
                                                        <p style="color: red;">{{ $message }}</p>
                                                        @enderror
                                                    </select>
                                                </td>
                                                <td>
                                                    <select id="product"
                                                            class="form-control form-select product-name product-select product"
                                                            name="products[0][product_id]">
                                                        <option value=""> Select Product</option>
                                                    </select>
                                                    @error('product_id')
                                                    <p style="color: red;">{{ $message }}</p>
                                                    @enderror
                                                </td>

                                                <td>
                                                    <select class="form-control form-select imei_id imei-id"
                                                            name="products[0][imei_id]" aria-label="Default select example"
                                                            required>
                                                        <option selected> Select Imi No</option>
                                                    </select>
                                                    @error('imiNo') <p style="color: red;">{{ $message }}</p> @enderror
                                                </td>


                                                <input type="hidden" class="form-control price-select price originalPrice"
                                                       name="products[0][price]" id="price" required />
                                                @error('price')
                                                <p style="color: red;">{{ $message }}</p>
                                                @enderror

                                                <td>
                                                    <div class="input-group">
                                                    <input type="number" class="form-control discount"
                                                           name="products[0][discount]"  id="discount"
                                                           required  />
                                                    <span class="input-group-text" id="basic-addon2">%</span>
                                                    </div>
                                                    @error('discount')
                                                    <p style="color: red;">{{ $message }}</p>
                                                    @enderror
                                                </td>

                                                <input type="hidden"
                                                       class="form-control discountAmount discount_amount"
                                                       name="products[0][discount_amount]" id="discountAmount" readonly
                                                       required />
                                                @error('discount_amount')
                                                <p style="color: red;">{{ $message }}</p>
                                                @enderror


                                                <td>
                                                    <div class="input-group">
                                                    <span class="input-group-text" id="basic-addon2">₹</span>
                                                    <input type="number" class="form-control priceSubTotal" readonly
                                                           id="priceSubTotal" name="products[0][price_subtotal]"
                                                           required />
                                                    </div>
                                                    @error('price_subtotal')
                                                    <p style="color: red;">{{ $message }}</p>
                                                    @enderror
                                                </td>

                                                <td>
                                                    <div class="input-group">
                                                    <input type="number" class="form-control tax" id="tax"
                                                           name="products[0][tax]" value="18" required />
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
                                                           name="products[0][tax_amount]" id="tax_amount" readonly
                                                           required />
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
                                                           id="totalAmount" name="products[0][price_total]" required />
                                                    </div>
                                                    @error('total')
                                                    <p style="color: red;">{{ $message }}</p>
                                                    @enderror
                                                </td>

                                                <td class="d-inline-flex gap-1">
                                                    <button type="button" class="btn btn-success add-row">+</button>
                                                    <button type="button" class="btn btn-danger remove-row">-
                                                    </button>
                                                    <button type="button" class="btn btn-secondary duplicate-row">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div>
                                                <label for="">Sub Total<span style="color: red"
                                                                             id="grandTotal">*</span></label>
                                                <input type="number" class="form-control subTotal" name="sub_total"
                                                       placeholder="sub total" readonly required />
                                                @error('sub_total')
                                                <p style="color: red;">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Tax Type<span style="color: red">*</span></label>
                                            <select class="form-control form-select taxType" name="tax_type"
                                                    aria-label="Default select example" required>
                                                <option  value="1" selected> CGST/SGST</option>
                                                <option value="2"> IGST</option>
                                            </select>

                                            @error('tax_type')
                                            <p style="color: red;">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for=""> Total Tax Amount<span
                                                    style="color: red">*</span></label>
                                            <input type="number" class="form-control totalTaxAmount"
                                                   name="total_tax_amount" id="total_tax_amount"
                                                   placeholder="Enter Total Tax Amount" readonly required />
                                            @error('tax_amount')
                                            <p style="color: red;">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

{{--                                    <div class="col-md-4">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label for="">Total Amount<span style="color: red">*</span></label>--}}
{{--                                            <input type="number" class="form-control finalTotalAmount"--}}
{{--                                                   name="total_amount" id="total_amount" placeholder="Enter Total  Amount"--}}
{{--                                                   readonly required />--}}
{{--                                            @error('total_amount')--}}
{{--                                            <p style="color: red;">{{ $message }}</p>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Rounded Amount<span style="color: red">*</span></label>
                                            <input type="number" class="form-control finalTotalAmount round_diff"
                                                   name="total_amount_rounded" id="total_amount" placeholder="Enter Total  Amount"
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
                                                   readonly required />
                                            @error('total_amount_rounded')
                                            <p style="color: red;">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Payment Method<span style="color: red">*</span></label>
                                            <select class="form-select paymentMethod" aria-label="Default select example"
                                                    name="payment_method">
                                                <option > Select Payment Method</option>
                                                <option value="1" selected>Online/Cash</option>
                                                <option value="2">Finance</option>
                                            </select>
                                            @error('payment_method')
                                            <p style="color: red;">{{ $message }}</p>
                                            @enderror
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
                                            <tr>
                                                <td>
                                                    <select name="payment[0][payment_mode]" id="mode"
                                                            class="form-control">
                                                        <option value="1">Cash</option>
                                                        <option value="2">Online</option>
{{--                                                        <option value="3">Finance</option>--}}
                                                    </select>
                                                </td>
                                                <td><input type="number" name="payment[0][amount]" id="amount"
                                                           class="form-control amount"></td>
                                                <td><input type="text" name="payment[0][reference_no]"
                                                           id="reference_no" class="form-control reference_no"></td>
                                                <td>
                                                    <button type="button"
                                                            class="btn btn-success add-payment-row">+</button>
                                                    <button type="button" class="btn btn-danger remove-payment-row">-
                                                    </button>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
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
                                                    >
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div>
                                                    <label> Select Finance </label>
                                                    <select class="form-select" name="finances_master_id"
                                                            aria-label="Default select example ">
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
                                                       onkeyup="SetFinanceAmount()" ;>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> Security Charge </label>
                                                <input type="number" name="mobile_security_charges" id="mobile_security_charges"
                                                       class="form-control required" placeholder="--Security Charge--"
                                                       onkeyup="SetFinanceAmount()" ;>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> Per Month EMI Charge </label>
                                                <input type="number" name="EMICharge" id="EMICharge"
                                                       class="form-control emicharge required" placeholder="--Per Month EMI Charge--"
                                                       value='0' onkeyup="SetMonthDuration()" ;>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> Down Payment </label>
                                                <input type="number" name="DownPayment" id="DownPayment"
                                                       class="form-control DownPayment required" placeholder="--Down Payment--"
                                                       onkeyup="SetFinanceAmount()" ;>
                                            </div>
                                        </div>
                                        <div class="col-md-4">

                                            <div class="form-group">
                                                <label> Payable Amount </label>
                                                <input type="number" name="FinanceAmount" id="FinanceAmount"
                                                       class="form-control FinanceAmount required" placeholder="--Payable Amount--"
                                                       readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> Month Duration </label>
                                                <input type="number" name="MonthDuration" id="MonthDuration"
                                                       class="form-control MonthDuration required" placeholder="--Month Duration--"
                                                       onkeyup="SetMonthDuration()" ;>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> Deduction Date </label>
                                                <select class="form-control  required" name="DeductionDate"
                                                        id="DeductionDate">
{{--                                                    @for ($i = 1; $i <= 28; $i++)--}}
{{--                                                        <option value="{{ $i }}" >{{ $i }}</option>--}}
{{--                                                    @endfor--}}
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
                                                       class="form-control Penalty required" placeholder="--Penalty Charges--">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"><br><br>
                                                <h6>
                                                    <div id="permonth" style="color: red;">
                                                    </div>
                                                </h6>
                                                <input type="hidden" name="permonthvalue" id="permonthvalue">
                                                <input type="hidden" name="financ_year" id="financ_year">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="card-action">
                                        <button class="btn btn-success" type="submit">Submit
                                        </button>
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

        $(document).ready(function () {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            const formattedDate = `${year}-${month}-${day}`;
            document.getElementById("datepicker").value = formattedDate;
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

            // function calculateRow($row) {
            //     const price = parseFloat($row.find('.price').val()) || 0;
            //     const total_Amount = parseFloat($row.find('.totalAmount').val()) || 0;
            //     const discount = parseFloat($row.find('.discount').val()) || 0;
            //     const tax = parseFloat($row.find('.tax').val()) || 0;
            //
            //     const discountAmount = (price * discount) / 100;
            //     const taxAmount =   total_Amount - (total_Amount / (1+(tax / 100)));
            //     const subtotal = total_Amount - taxAmount - discountAmount;
            //     const totalAmount = price - discountAmount;
            //
            //     $row.find('.discountAmount').val(discountAmount.toFixed(2));
            //     $row.find('.priceSubTotal').val(subtotal.toFixed(2));
            //     $row.find('.taxAmount').val(taxAmount.toFixed(2));
            //     $row.find('.totalAmount').val(totalAmount.toFixed(2));
            // }

            function calculateRow($row, isTotalAmountEdited) {
                let price = parseFloat($row.find('.price').val()) || 0;
                let discount = parseFloat($row.find('.discount').val()) || 0;
                let tax = parseFloat($row.find('.tax').val()) || 0;
                let totalAmount = parseFloat($row.find('.totalAmount').val()) || 0;

                let discountAmount = 0;
                if (isTotalAmountEdited) {
                    // When user edited totalAmount manually:
                    discountAmount = (totalAmount * discount) / 100;
                    price = totalAmount + discountAmount;  // back calculate price
                } else {
                    // When user edited price/discount/tax:
                    discountAmount = (price * discount) / 100;
                    totalAmount = price - discountAmount;
                    $row.find('.totalAmount').val(totalAmount.toFixed(2));
                }

                const taxAmount = totalAmount - (totalAmount / (1 + (tax / 100)));
                const subtotal = totalAmount - taxAmount;

                $row.find('.discountAmount').val(discountAmount.toFixed(2));
                $row.find('.priceSubTotal').val(subtotal.toFixed(2));
                $row.find('.taxAmount').val(taxAmount.toFixed(2));
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
            $(document).on('input', '.price, .discount, .tax ', function () {
                const $row = $(this).closest('tr');
                calculateRow($row, false);
                calculateGrandTotal();
            });

            // When total amount (only) changes
            $(document).on('input', '.totalAmount', function () {
                const $row = $(this).closest('tr');
                calculateRow($row, true);
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
                resetActionButtons();
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

        function SetFinanceAmount() {
            var DownPayment = $("#DownPayment").val();
            var Processing = $("#Processing").val();
            var EMICharge = $("#EMICharge").val();
            var Price = $("#total_amount_rounded").val();
            var MonthDuration = $("#MonthDuration").val();
            var SecurityDeposit = $("#mobile_security_charges").val();
            total = (parseInt(Price) + parseInt(Processing) + parseInt(SecurityDeposit)) - DownPayment;

            $("#FinanceAmount").val(total);
            $("#MonthDuration").val("");
            $("#permonth").html("");
        }


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
                            calculateRow();
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

            $('#name, #city').inputmask({
                regex: "^[a-zA-Z ]*$",
                placeholder: ''
            });

            $('input[name="ref_mobile_no"]').mask('0000000000');

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
