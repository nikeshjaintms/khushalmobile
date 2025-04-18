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
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Customer<span style="color: red">*</span></label>
                                            <select class="form-select customer" aria-label="Default select example" name="customer_id">
                                                <option selected> Select Customer</option>
                                                @foreach($customers as $item)
                                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                                    <option value="{{$item->id}}" {{ $selectedCustomer->contains($item->id) ? 'selected' : '' }}>{{$item->name}}</option>
                                                @endforeach
                                                @error('customer') <p style="color: red;">{{ $message }}</p> @enderror
                                            </select>

                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div>
                                                <label for="">Invoice No<span style="color: red">*</span></label>
                                                <input type="text" class="form-control" name="invoice_no" id="invoice_no" value="{{$data->invoice_no}}" readonly required/>
                                                @error('invoice_no') <p style="color: red;">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div>
                                                <label for="">Invoice Date<span style="color: red">*</span></label>
                                                <input id="datepicker" name="invoice_date" class="form-control datepicker" value="{{$data->invoice_date}}" placeholder="select Date"/>
                                                @error('invoice_date') <p style="color: red;">{{ $message }}</p> @enderror
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
                                                <th scope="col">IMI No</th>
                                                <th scope="col">Price</th>
                                                <th scope="col">Discount</th>
                                                <th scope="col">Discount Amount</th>
                                                <th scope="col">Price Sub Total</th>
                                                <th scope="col">Tax</th>
                                                <th scope="col">Tax Amount</th>
                                                <th scope="col">Total</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody class="table-group-divider" id="add-table-row">
                                            <tr>
                                                <th scope="row" class="row-index">1</th>
                                                <td>
                                                    <select class="form-control form-select
                                                    brand-name brand-select" name="products[0][brand_id]"
                                                            aria-label="Default select example" required>
                                                        <option value="">Select Brand</option>

                                                        {{--                                                            @foreach($brands as $brand)--}}
                                                        {{--                                                                <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }} {{ $selectedBrand->contains($brand->id) ? 'selected' : '' }}>--}}
                                                        {{--                                                                    {{ $brand->name }}--}}
                                                        {{--                                                                </option>--}}
                                                        {{--                                                            @endforeach--}}
                                                        @error('brand_id') <p style="color: red;">{{ $message }}</p> @enderror
                                                    </select>
                                                </td>
                                                <td>
                                                    <select id="product" class="form-control form-select product-name product-select product" name="products[0][product_id]">
                                                        <option value=""> Select Product</option>
                                                        @foreach($products as $product)
                                                            <option value="{{ $product->id }}" {{ $product->id == $selectedProductId ? 'selected' : '' }}>{{ $product->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    {{--                                                        <select id="product" class="form-control form-select product-name product-select product" name="products[0][product_id]">--}}
                                                    {{--                                                            <option value="">Select Product</option>--}}
                                                    {{--                                                            @foreach($products as $product)--}}
                                                    {{--                                                                <option value="{{ $product->id }}" {{ $product->id == $selectedProductId ? 'selected' : '' }}>--}}
                                                    {{--                                                                    {{ $product->name }}--}}
                                                    {{--                                                                </option>--}}
                                                    {{--                                                            @endforeach--}}
                                                    {{--                                                        </select>--}}

                                                    @error('product_id') <p style="color: red;">{{ $message }}</p> @enderror
                                                </td>

                                                <td>
                                                    <select class="form-control form-select imi-no" name="imiNo" aria-label="Default select example" required>
                                                        <option value="" disabled {{ empty($selectedImi) ? 'selected' : '' }}>Select IMI No</option>
                                                        @foreach($imiNumbers as $imi)
                                                            <option value="{{ $imi }}" {{ $imi == $selectedImi ? 'selected' : '' }}>{{ $imi }}</option>
                                                        @endforeach
                                                    </select>

                                                    {{--                                                    <select class="form-control form-select imi-no" name="imiNo" aria-label="Default select example" required>--}}
{{--                                                        <option selected> Select Imi No</option>--}}
{{--                                                    </select>--}}
                                                    @error('imiNo') <p style="color: red;">{{ $message }}</p> @enderror
                                                </td>

                                                <td>
                                                    <input type="text" class="form-control price-select price" name="products[0][price]" id="price" value="{{$data1->price}}" readonly required/>
                                                    @error('price') <p style="color: red;">{{ $message }}</p> @enderror
                                                </td>

                                                <td>
                                                    <input type="text" class="form-control discount" name="products[0][discount]" id="discount" value="{{$data1->discount}}" required/>
                                                    @error('discount') <p style="color: red;">{{ $message }}</p> @enderror
                                                </td>

                                                <td>
                                                    <input type="text" class="form-control discountAmount discount_amount" name="products[0][discount_amount]" value="{{$data1->discount_amount}}" id="discountAmount" readonly required/>
                                                    @error('discount_amount') <p style="color: red;">{{ $message }}</p> @enderror
                                                </td>

                                                <td>
                                                    <input type="text" class="form-control priceSubTotal" id="priceSubTotal" name="products[0][price_subtotal]" value="{{$data1->price_subtotal}}" readonly required/>
                                                    @error('price_subtotal') <p style="color: red;">{{ $message }}</p> @enderror
                                                </td>

                                                <td>
                                                    <input type="text" class="form-control tax" id="tax" name="products[0][tax]" value="{{$data1->tax}}" required/>
                                                    @error('tax') <p style="color: red;">{{ $message }}</p> @enderror
                                                </td>

                                                <td>
                                                    <input type="text" class="form-control taxAmount tax-amount" name="products[0][tax_amount]" id="tax_amount" value="{{$data1->tax_amount}}" readonly required/>
                                                    @error('tax_amount') <p style="color: red;">{{ $message }}</p> @enderror
                                                </td>

                                                <td>
                                                    <input type="text" class="form-control total total-amount totalAmount" id="totalAmount" name="products[0][price_total]" value="{{$data1->price_total}}" readonly required/>
                                                    @error('total') <p style="color: red;">{{ $message }}</p> @enderror
                                                </td>

                                                <td class="d-inline-flex gap-1">
                                                    <button type="button" class="btn btn-success add-row">+</button>
                                                    <button type="button" class="btn btn-danger remove-row">-</button>
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
                                                <label for="">Sub Total<span style="color: red" id="grandTotal">*</span></label>
                                                <input type="text" class="form-control subTotal" name="sub_total" placeholder="sub total" value="{{$data->sub_total}}" readonly required/>
                                                @error('sub_total') <p style="color: red;">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div>
                                                <label for="">Tax Type<span style="color: red">*</span></label>
                                                <select class="form-control form-select taxType" name="tax_type" aria-label="Default select example" required>
                                                    <option selected>Select Tax Type</option>
                                                    <option value="1" {{ $data->tax_type == 1 ? 'selected' : '' }}>CGST/SGST</option>
                                                    <option value="2" {{ $data->tax_type == 2 ? 'selected' : '' }}>IGST</option>
                                                </select>

                                                @error('tax_type') <p style="color: red;">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div>
                                                <label for=""> Total Tax Amount<span style="color: red">*</span></label>
                                                <input type="text" class="form-control totalTaxAmount" name="total_tax_amount" id="total_tax_amount" placeholder="Enter Total Tax Amount" value="{{$data->total_tax_amount}}" readonly required/>
                                                @error('tax_amount') <p style="color: red;">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div>
                                                <label for="">Total Amount<span style="color: red">*</span></label>
                                                <input type="text" class="form-control finalTotalAmount" name="total_amount" id="total_amount" placeholder="Enter Total  Amount" value="{{$data->total_amount}}" readonly required/>
                                                @error('total_amount') <p style="color: red;">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div>
                                                <label for="">Payment Method<span style="color: red">*</span></label>
                                                <select class="form-select paymentMethod" aria-label="Default select example" name="payment_method">
                                                    <option selected> Select Payment Method</option>
                                                    <option value="1" {{ $data->payment_method == 1 ? 'selected' : '' }} >Online</option>
                                                    <option value="2" {{ $data->payment_method == 2 ? 'selected' : '' }}>Cash</option>
                                                    <option value="3" {{ $data->payment_method == 3 ? 'selected' : '' }}>Finance</option>
                                                </select>
                                                @error('payment_method') <p style="color: red;">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3" id="online_detail">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Reference number </label>
                                                <input type="text" name="reference_no" id="reference_no"
                                                       class="form-control required" placeholder="Enter reference_no"
                                                >
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row mt-3" id="finance_detail">
                                        <h4>Finance Details</h4>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div>
                                                    <label> Select Finance </label>
                                                    <select class="select2 form-control required" name="Finance"
                                                            id="Finance">
                                                        <option value=""> --Select
                                                            Finance--
                                                        </option>
                                                        <option value="Bajaj Finance"> Bajaj
                                                            Finance
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> Processing Fee </label>
                                                <input type="text" name="Processing" id="Processing"
                                                       class="form-control required" placeholder="--Processing Fee--"
                                                       onkeyup="SetFinanceAmount()" ;>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> Per Month EMI Charge </label>
                                                <input type="text" name="EMICharge" id="EMICharge"
                                                       class="form-control required" placeholder="--Per Month EMI Charge--"
                                                       value='0' onkeyup="SetMonthDuration()" ;>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> Down Payment </label>
                                                <input type="text" name="DownPayment" id="DownPayment"
                                                       class="form-control required" placeholder="--Down Payment--"
                                                       onkeyup="SetFinanceAmount()" ;>
                                            </div>
                                        </div>
                                        <div class="col-md-4">

                                            <div class="form-group">
                                                <label> Payable Amount </label>
                                                <input type="text" name="FinanceAmount" id="FinanceAmount"
                                                       class="form-control required" placeholder="--Payable Amount--" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> Month Duration </label>
                                                <input type="text" name="MonthDuration" id="MonthDuration"
                                                       class="form-control required" placeholder="--Month Duration--"
                                                       onkeyup="SetMonthDuration()" ;>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> Deduction Date </label>
                                                <select class="form-control required" name="DeductionDate"
                                                        id="DeductionDate">
                                                        <?php
                                                        for ($i = 1; $i <= 28; $i++) {
                                                            echo '<option value="' . $i . '">' . $i . '</option>';
                                                        }
                                                        ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> Penalty Charges </label>
                                                <input type="text" name="Penalty" id="Penalty"
                                                       class="form-control required" placeholder="--Penalty Charges--">
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
        document.addEventListener('DOMContentLoaded', function () {
            const tbody = document.getElementById('add-table-row');

            // Store dropdown options ONCE
            const brandOptionsHTML = document.querySelector('.brand-name')?.innerHTML || '';
            const productOptionsHTML = document.querySelector('.product-name')?.innerHTML || '';
            const imiOptionsHTML = document.querySelector('.imei_id')?.innerHTML || '';

            function updateButtons() {
                const rows = tbody.querySelectorAll('tr');
                rows.forEach((row, index) => {
                    const addBtn = row.querySelector('.add-row');
                    if (addBtn) {
                        addBtn.style.display = (index === rows.length - 1) ? 'inline-block' : 'none';
                    }
                    const th = row.querySelector('th');
                    if (th) th.textContent = index + 1;
                });
            }

            function createRow(data = {}) {
                const row = document.createElement('tr');
                row.innerHTML = `

        <th scope="row">1</th>
        <td>
            <select class="form-control form-select brand-name brand-select">
                ${brandOptionsHTML}
            </select>
        </td>
        <td>
            <select class="form-control form-select product-name product-select" class="product" name="product_id">
                ${productOptionsHTML}
            </select>

</td>
<td>
<select class="form-control form-select imei_id" name="imei_id[]">
${imiOptionsHTML}
            </select>
        </td>
        <td><input type="text" class="form-control price"  id="price"  value="${data.mrp || ''}" readonly required /></td>
        <td><input type="text" class="form-control discount" value="${data.discount || ''}"   required /></td>
        <td><input type="text" class="form-control discount_amount discountAmount" value="${data.discount_amount || ''}" id="discountAmount" readonly required /></td>
<td><input type="text" class="form-control priceSubTotal" value="${data.priceSubTotal || ''}" readonly required /></td>
<td><input type="text" class="form-control tax" value="${data.tax || ''}"  required /></td>
<td><input type="text" class="form-control tax-amount taxAmount" value="${data.tax_amount || ''}" readonly required /></td>
        <td><input type="text" class="form-control total total-amount totalAmount" value="${data.total || ''}"  readonly required /></td>
        <td class="d-inline-flex gap-1">
            <button type="button" class="btn btn-success add-row">+</button>
            <button type="button" class="btn btn-danger remove-row">-</button>
            <button type="button" class="btn btn-secondary duplicate-row">
                <i class="fas fa-copy"></i>
            </button>
        </td>

                    `;
                const priceInput = row.querySelector('.price');
                const discountInput = row.querySelector('.discount');
                const taxInput = row.querySelector('.tax');

                function handleInput() {
                    const price = parseFloat(priceInput.value) || 0;
                    const discount = parseFloat(discountInput.value) || 0;
                    const tax = parseFloat(taxInput.value) || 0;

                    const discountAmount = (price * discount) / 100;
                    const priceSubTotal = price - discountAmount;
                    const taxAmount = (price * tax) / 100;
                    const totalAmount = priceSubTotal + taxAmount;

                    row.querySelector('.discountAmount').value = discountAmount.toFixed(2);
                    row.querySelector('.taxAmount').value = taxAmount.toFixed(2);
                    row.querySelector('.totalAmount').value = totalAmount.toFixed(2);
                    row.querySelector('.priceSubTotal').value = priceSubTotal.toFixed(2);

                    updateGrandTotal();
                    updateTotalTaxAmount();
                    updateTotalAmount();

                }

                priceInput.addEventListener('input', handleInput);
                discountInput.addEventListener('input', handleInput);
                taxInput.addEventListener('input', handleInput);

                tbody.appendChild(row);
                updateButtons();
            }

            tbody.addEventListener('click', function (e) {
                const target = e.target.closest('button');

                if (!target) return;

                if (target.classList.contains('add-row')) {
                    createRow();
                }

                if (target.classList.contains('remove-row')) {
                    const row = target.closest('tr');
                    if (tbody.querySelectorAll('tr').length > 1) {
                        row.remove();
                        updateButtons();
                        updateGrandTotal();
                        updateTotalTaxAmount();
                        updateTotalAmount();
                    }
                }

                if (target.classList.contains('duplicate-row')) {
                    const row = target.closest('tr');
                    const data = {
                        price: row.querySelector('.price')?.value,
                        // discount: row.querySelector('.discount')?.value,
                        discount_amount: row.querySelector('.discount_amount')?.value,
                        // total: row.querySelector('.total')?.value,

                        priceSubTotal: row.querySelector('.priceSubTotal')?.value || '',
                        // tax: row.querySelector('.tax')?.value || '',
                        tax_amount: row.querySelector('.tax-amount')?.value || '',

                        total: row.querySelector('.total')?.value || ''
                    };
                    const selectedProduct = row.querySelector('.product-name')?.value;
                    const selectedBrand = row.querySelector('.brand-name')?.value;
                    const selectedImi = row.querySelector('.imei_id ')?.value;

                    createRow({
                        ...data
                    });

                    const newRow = tbody.lastElementChild;
                    newRow.querySelector('.product-name').value = selectedProduct;
                    newRow.querySelector('.brand-name').value = selectedBrand;
                    newRow.querySelector('.imei_id ').value = selectedImi;
                    newRow.querySelector('.price').value = data.mrp;

                    updateGrandTotal();
                    updateRowCalculations(newRow);

                }
            });

            updateButtons();
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
                success: function (data) {
                    data = data.split(',');
                    $("#bill_no").val(data[0]);
                    $("#financ_year").val(data[1]);
                    $("#InvoiceNo").val(data[2]);
                }
            });
        }

        function updateRowCalculations(row) {
            const price = parseFloat(row.querySelector('.price').value) || 0;
            const discount = parseFloat(row.querySelector('.discount').value) || 0;
            const tax = parseFloat(row.querySelector('.tax').value) || 0;

            const discountAmount = (price * discount) / 100;
            const taxAmount = (price * tax) / 100;
            const priceSubTotal = price - discountAmount;
            const totalAmount = priceSubTotal + taxAmount;

            row.querySelector('.discountAmount').value = discountAmount.toFixed(2);
            row.querySelector('.taxAmount').value = taxAmount.toFixed(2);
            row.querySelector('.priceSubTotal').value = priceSubTotal.toFixed(2);
            row.querySelector('.totalAmount').value = totalAmount.toFixed(2);
        }

        function updateGrandTotal() {
            let grandTotal = 0;
            document.querySelectorAll('.priceSubTotal').forEach(input => {
                grandTotal += parseFloat(input.value) || 0;
            });

            // Update input field instead of span
            const subTotalInput = document.querySelector('.subTotal');
            if (subTotalInput) {
                subTotalInput.value = grandTotal.toFixed(2);
            }
        }

        function updateTotalTaxAmount() {
            let totalTaxAmount = 0;
            document.querySelectorAll('.taxAmount').forEach(input => {
                totalTaxAmount += parseFloat(input.value) || 0;
            });

            // Update input field instead of span
            const totalTaxAmountInput = document.querySelector('.totalTaxAmount');
            if (totalTaxAmountInput) {
                totalTaxAmountInput.value = totalTaxAmount.toFixed(2);
            }
        }

        function updateTotalAmount() {
            let FinalTotalAmount = 0;
            document.querySelectorAll('.totalAmount').forEach(input => {
                FinalTotalAmount += parseFloat(input.value) || 0;
            });

            // Update input field instead of span
            const FinalTotalAmountInput = document.querySelector('.finalTotalAmount');
            if (FinalTotalAmountInput) {
                FinalTotalAmountInput.value = FinalTotalAmount.toFixed(2);
            }
        }

        function SetFinanceAmount() {
            var DownPayment = $("#DownPayment").val();
            var Processing = $("#Processing").val();
            var EMICharge = $("#EMICharge").val();
            var Price = $("#total_amount").val();
            var MonthDuration = $("#MonthDuration").val();
            total = (parseInt(Price) + parseInt(Processing)) - DownPayment;

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
            var Price = $("#total_amount").val();
            var Processing = $("#Processing").val();
            var DownPayment = $("#DownPayment").val();
            if (EMICharge == "") {
                EMICharge = '0';
            } else {
                EMICharge = EMICharge;
            }
            var ChargeTotal = parseInt(EMICharge) * parseInt(MonthDuration);
            Financetotal = (parseInt(Price) + parseInt(Processing)) - DownPayment;
            AmountTotal = parseInt(Financetotal) + parseInt(ChargeTotal);

            $("#FinanceAmount").val(Math.round(AmountTotal));
            var total = AmountTotal / MonthDuration;
            $("#permonthvalue").val(Math.round(total));
            $("#permonth").html("EMI Per Month : " + Math.round(total));
        }

        // Attach event listeners
        document.querySelectorAll('.price, .discount, .tax').forEach(input => {
            input.addEventListener('input', function () {
                const row = this.closest('tr');
                updateRowCalculations(row);
                updateGrandTotal();
                updateTotalTaxAmount();
                updateTotalAmount();
            });
        });


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
                        console.log(data.mrp);
                    }
                });
            } else {
                $('.price').val('');
            }
        });

        $(document).ready(function () {

            // Initially hide the finance detail section
            $('#finance_detail').hide();
            $('#online_detail').hide();

            // Listen to changes on the payment method dropdown
            $('.paymentMethod').change(function () {
                var selected = $(this).val();
                if (selected == '1') { // 3 = online
                    $('#online_detail').show();
                } else {
                    $('#online_detail').hide();
                    // Optional: clear all inputs in the finance detail section when hidden
                    $('#online_detail').find('input, select').val('');
                }

                if (selected == '3') { // 3 = Finance
                    $('#finance_detail').show();
                } else {
                    $('#finance_detail').hide();
                    // Optional: clear all inputs in the finance detail section when hidden
                    $('#finance_detail').find('input, select').val('');
                    $('#permonth').text('');
                    $('#permonthvalue').val('');
                }

            });


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

            $(document).on('change', '.product-select', function () {
                let $row = $(this).closest('tr'); // get current row
                let productId = $(this).val();
                let imeiDropdown = $row.find('.imei_id');
                let urK = "{{ route('admin.sale.get-imeis', ':productId') }}".replace(':productId',
                    productId);

                if (productId) {
                    $.ajax({
                        url: urK,
                        method: 'GET',
                        success: function (data) {
                            imeiDropdown.empty().append(
                                '<option value="">Select IMEI No</option>');
                            $.each(data, function (id, imei) {
                                imeiDropdown.append('<option value="' + id + '">' +
                                    imei + '</option>');
                            });
                        },
                        error: function () {
                            alert('Could not fetch IMEI numbers.');
                        }
                    });
                }
            });

            $('input[name="phone"]').mask('0000000000');

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


        // $(document).ready(function () {
        //
        //     $('input[name="phone"]').mask('0000000000');
        //
        //     $('#name, #city').inputmask({
        //         regex: "^[a-zA-Z ]*$",
        //         placeholder: ''
        //     });
        //     $("#saleForm").validate({
        //         onfocusout: function (element) {
        //             this.element(element); // Validate the field on blur
        //         },
        //         onkeyup: false, // Optional: Disable validation on keyup for performance
        //         rules: {
        //             name: {
        //                 required: true,
        //                 minlength: 3,
        //                 maxlength: 50,
        //             },
        //             phone: {
        //                 required: true
        //             },
        //
        //             address: {
        //                 required: true
        //             },
        //             city: {
        //                 required: true
        //             }
        //
        //         },
        //         messages: {
        //             name: {
        //                 required: "Please Enter Dealer Name",
        //                 minleght: "Please Enter Minimum 3 Characters",
        //                 maxlength: "Please Enter Maximum 50 Characters",
        //
        //             },
        //             phone: {
        //                 required: "Please enter phone number"
        //             },
        //             address: {
        //                 required: "Please enter address"
        //             },
        //             city: {
        //                 required: "please enter city"
        //             }
        //         },
        //         errorClass: "text-danger",
        //         errorElement: "span",
        //         highlight: function (element) {
        //             $(element).addClass("is-invalid");
        //         },
        //         unhighlight: function (element) {
        //             $(element).removeClass("is-invalid");
        //         },
        //         submitHandler: function (form) {
        //             // Handle successful validation here
        //             form.submit();
        //         }
        //     });
        // });
    </script>
@endsection
