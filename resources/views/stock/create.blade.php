@extends('layouts.app')
{{-- @if (Auth::guard('admin')->check()) --}}
@section('title', 'Admin Panel')

{{-- @endif --}}

@section('content-page')
    <style>
        .table>tbody>tr>td,
        .table>tbody>tr>th {
            padding: 16px 5px !important;
        }

        .input-wrapper {
            margin-bottom: 10px;
        }
        .error-message {
            color: red;
            font-size: 14px;
            display: block; /* important */
            margin-top: 5px;
        }

    </style>
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Stock</h3>
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
                        <a href="{{ route('admin.stock.index') }}">Stock</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Add Stock</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Add Stock</div>
                        </div>
                        <form method="POST" action="{{ route('admin.stock.store') }}" id="stockForm">
                            @csrf
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-12">
                                        <table id="product-table" class="display table table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th>Brand</th>
                                                <th>Product</th>
                                                <th>IMEI</th>
                                                <th>Color</th>
                                                <th>Price</th>
                                                <th>Discount</th>
                                                {{-- <th>Discount Amount</th> --}}
                                                <th> SubTotal</th>
                                                <th>Tax</th>
                                                {{-- <th>Tax Amount</th> --}}
                                                <th>total</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            @foreach(old('imei', ['']) as $index => $imei)
                                                <tbody>
                                                <tr>
                                                    <td><select name="brand_id[]" class="form-control brand-select"
                                                                id="brand_id" required>
                                                            <option value="">Select Brand</option>
                                                            @foreach ($brands as $brand)
                                                                <option value="{{ $brand->id }}">{{ $brand->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('brand_id[]')
                                                        <div class="text-red-500">{{ $message }}</div>
                                                        @enderror

                                                    </td>
                                                    <td><select name="product_id[]" class="form-control product-select"
                                                                id="product_id"  required>
                                                            <option value="" >Select Product</option>
                                                        </select>
                                                        @error('product_id[]')
                                                        <div class="text-red-500">{{ $message }}</div>
                                                        @enderror
                                                    </td>


                                                    <td style="width: fit-content;">
                                                        <div class="input-wrapper">
                                                            <input type="text" class="form-control imei-input" name="imei[]" value="{{ old('imei.' . $index, $imei) }}" required>
                                                            <div class="error-message d-none"></div>
                                                            <div id="productReturned" style="margin-top: 10px; font-weight: bold; display: none;"></div>
                                                        </div>
                                                        @if ($errors->has("imei.$index"))
                                                            <div class="text-danger" >{{ $errors->first("imei.$index") }}</div>
                                                        @endif
                                                        <span id="imei-error-id" class="text-danger d-none">One or more IMEI numbers already exist in the system.</span>
                                                    </td>

                                                    <td><input type="text" class="form-control" name="color[]"
                                                               id="color" value="{{old('color[]')}}" required>
                                                        @error('color[]')
                                                        <div class="text-red-500">{{ $message }}</div>
                                                        @enderror
                                                    </td>
                                                    <td><input type="number" class="form-control" name="price[]"
                                                               id="price" value="0">
                                                        @error('price[]')
                                                        <div class="text-red-500">{{ $message }}</div>
                                                        @enderror
                                                    </td>
                                                    <td><input type="number" class="form-control" name="discount[]"
                                                               id="discount" value="0" >
                                                        @error('discount[]')
                                                        <div class="text-red-500">{{ $message }}</div>
                                                        @enderror
                                                    </td>
                                                    <input type="hidden" class="form-control" readonly
                                                           name="discount_amount[]" id="discount_amount">
                                                    <td><input type="text" class="form-control" readonly
                                                               name="price_subtotal[]" id="price_subtotal" value="0">
                                                        @error('price_subtotal[]')
                                                        <div class="text-red-500">{{ $message }}</div>
                                                        @enderror
                                                    </td>
                                                    <td><input type="number" class="form-control" name="tax[]"
                                                               id="tax"  value="18">
                                                        @error('tax[]')
                                                        <div class="text-red-500">{{ $message }}</div>
                                                        @enderror
                                                    </td>
                                                    <input type="hidden" class="form-control" readonly
                                                           name="tax_amount[]" id="tax_amount" required>
                                                    <td><input type="number" class="form-control" readonly
                                                               name="product_total[]" id="product_total" value="0">
                                                        @error('product_total[]')
                                                        <div class="text-red-500">{{ $message }}</div>
                                                        @enderror
                                                    </td>
                                                    <td class="gap-1    ">
                                                        <button type="button"
                                                                class="btn btn-success btn-sm add-row mb-1">+</button>
                                                        <button type="button"
                                                                class="btn btn-danger btn-sm remove-row d-none mb-1">-</button>
                                                        <button type="button"
                                                                class="btn btn-primary btn-sm duplicate-row"><i
                                                                class="fas fa-copy"></i></button>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card-action">
                                <button class="btn btn-success" type="submit">Submit</button>
                                <a href="{{ route('admin.stock.index') }}" class="btn btn-danger">Cancel</a>
                                <div id="imeiResults"></div>
                            </div>
                        </form>
                        <div id="result"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-script')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <script>

        $(document).ready(function() {

            document.querySelectorAll(".imei-input").forEach(input => {
                input.addEventListener("blur", function () {
                    const value = input.value.trim();
                    const errorContainer = input.closest('.imei-wrapper').querySelector('.imei-error');
                    // Clear old message
                    errorContainer.textContent = '';
                    if (value !== '') {
                        fetch(`/check-imei?imei=${value}`)
                            .then(res => res.json())
                            .then(data => {
                                if (data.exists) {
                                    errorContainer.textContent = `IMEI ${value} already exists in the system.`;
                                }
                            });
                    }
                });
            });

            // Function to get next serial number
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            const formattedDate = `${year}-${month}-${day}`;
            //document.getElementById("po_date").value = formattedDate;

            function resetActionButtons() {
                const $rows = $('#product-table tbody tr');

                if ($rows.length === 1) {
                    // Only one row: hide "Remove" button
                    $rows.find('.remove-row').addClass('d-none');
                    $rows.find('.add-row').removeClass('d-none'); // Allow adding
                } else {
                    $rows.each(function(index, row) {
                        const $row = $(row);
                        const isLastRow = index === $rows.length - 1;

                        $row.find('.remove-row').removeClass('d-none'); // Show remove
                        $row.find('.add-row').toggleClass('d-none', !isLastRow); // Show add only on last
                    });
                }
            }

            function calculateRow($row) {
                let price = parseFloat($row.find('input[name="price[]"]').val()) || 0;
                let discount = parseFloat($row.find('input[name="discount[]"]').val()) || 0;
                let tax = parseFloat($row.find('input[name="tax[]"]').val()) || 0;

                let discountAmount = price * (discount / 100);
                let subtotal = price - discountAmount;
                let taxAmount = subtotal * (tax / 100);
                let total = subtotal + taxAmount;

                $row.find('input[name="discount_amount[]"]').val(discountAmount.toFixed(2));
                $row.find('input[name="price_subtotal[]"]').val(subtotal.toFixed(2));
                $row.find('input[name="tax_amount[]"]').val(taxAmount.toFixed(2));
                $row.find('input[name="product_total[]"]').val(total.toFixed(2));

                calculateGrandTotal();
            }

            function calculateGrandTotal() {
                let subtotalSum = 0;
                let taxSum = 0;
                let totalSum = 0;

                $('#product-table tbody tr').each(function() {
                    subtotalSum += parseFloat($(this).find('input[name="price_subtotal[]"]').val()) || 0;
                    taxSum += parseFloat($(this).find('input[name="tax_amount[]"]').val()) || 0;
                    totalSum += parseFloat($(this).find('input[name="product_total[]"]').val()) || 0;
                });

                $('input[name="sub_total"]').val(subtotalSum.toFixed(2));
                $('input[name="total_tax_amount"]').val(taxSum.toFixed(2));
                $('input[name="total"]').val(totalSum.toFixed(2));

                const roundedTotal = (totalSum - Math.floor(totalSum) >= 0.5)
                    ? Math.ceil(totalSum)
                    : Math.floor(totalSum);

                $('.total_rounded').val(roundedTotal.toFixed(2));

                const diff = roundedTotal - totalSum;
                $('.round_diff').val(diff.toFixed(2));


            }
            $(document).on('input', 'input[name="price[]"], input[name="discount[]"], input[name="tax[]"]',
                function() {
                    let $row = $(this).closest('tr');
                    calculateRow($row);
                });

            // Add new row
            $(document).on('click', '.add-row', function() {
                let $row = $(this).closest('tr');
                let $newRow = $row.clone();

                // Clear input and select values
                $newRow.find('input').val('');
                $newRow.find('select').val('');
                $newRow.find('input[name="tax[]"]').val(18);

                // Show remove button
                $newRow.find('.remove-row').removeClass('d-none');

                // Append the row
                $row.closest('tbody').append($newRow);
                resetActionButtons();
                calculateGrandTotal();
            });

            // Remove row
            $(document).on('click', '.remove-row', function() {
                if ($('#product-table tbody tr').length > 1) {
                    $(this).closest('tr').remove();
                    resetActionButtons();
                    calculateGrandTotal();
                }

            });

            // Duplicate row
            $(document).on('click', '.duplicate-row', function() {
                let $row = $(this).closest('tr');
                let $dupRow = $row.clone();

                // Preserve values

                $dupRow.find('input').val('');
                $dupRow.find('input[name="tax[]"]').val(18);


                let selectedBrand = $row.find('.brand-select').val();
                let selectedProduct = $row.find('.product-select').val();

                $dupRow.find('.brand-select').val(selectedBrand);
                $dupRow.find('.remove-row').removeClass('d-none');




                // Append duplicated row
                $row.closest('tbody').append($dupRow);
                resetActionButtons();
                calculateGrandTotal();

                // Load products via AJAX
                if (selectedBrand) {
                    let $productSelect = $dupRow.find('.product-select');
                    $productSelect.html('<option value="">Loading...</option>');

                    $.ajax({
                        url: "{{ route('admin.product.getproducts', ':brand_id') }}"
                            .replace(
                                ':brand_id', selectedBrand),
                        type: 'GET',
                        success: function(response) {
                            $productSelect.empty().append(
                                '<option value="">Select Product</option>');
                            $.each(response, function(index, product) {
                                let selected = (product.id == selectedProduct) ?
                                    'selected' : '';
                                $productSelect.append('<option value="' +
                                    product.id +
                                    '" ' + selected + '>' + product
                                        .product_name +
                                    '</option>');
                            });
                        },
                        error: function() {
                            $productSelect.html(
                                '<option value="">Error loading products</option>');
                        }
                    });
                }
            });

            // Brand change => load product
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

            // Initial product fetch for default brand select (if any)
            $(document).on('change', '#brand_id', function() {
                let brandId = $(this).val();
                let $productSelect = $(this).closest('tr').find('#product_id');

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
            $.validator.addMethod("uniqueIMEI", function(value, element) {
                // Get all IMEI inputs
                var allIMEIs = $("input[name='imei[]']");
                var currentValue = $(element).val();
                var firstIndex = -1;
                var currentIndex = -1;

                allIMEIs.each(function(index) {
                    if ($(this).val() === currentValue) {
                        if (firstIndex === -1) {
                            firstIndex = index;
                        }
                        if (this === element) {
                            currentIndex = index;
                        }
                    }
                });

                // If this field is the first one with this value → valid
                // If this is a duplicate (appears after the first) → invalid
                return currentIndex === firstIndex;
            }, "IMEI must be unique.");

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }});
            // Form validation

            $("#stockForm").validate({
                onfocusout: function(element) {
                    this.element(element);
                },
                onkeyup: false,
                rules: {
                    "price[]": {
                        number: true,
                        min: 0
                    },
                    "color[]": {
                        required: true
                    },
                    "imei[]": {
                        required: true,
                        minlength: 15,
                        maxlength: 15,
                        uniqueIMEI: true,
                    },
                    "discount[]": {
                        number: true,
                        min: 0
                    },
                    "discount_amount[]": {
                        number: true,
                        min: 0
                    },
                    "price_subtotal[]": {
                        number: true,
                        min: 0
                    },
                    "tax[]": {
                        required: true,
                        number: true,
                        min: 0
                    },
                    "tax_amount[]": {
                        number: true,
                        min: 0
                    },
                    "product_total[]": {
                        number: true,
                        min: 0
                    },
                },
                messages: {

                    "price[]": {
                        number: "Please enter a valid number",
                        min: "Price must be greater than 0"
                    },
                    "color[]": {
                        required: "Color is required"
                    },
                    "imei[]": {
                        required: "IMEI is required",
                        minlength: "IMEI must be 15 digits",
                        maxlength: "IMEI must be 15 digits",
                    },
                    "discount[]": {
                        number: "Please enter a valid number",
                        min: "Discount cannot be negative"
                    },
                    "discount_amount[]": {
                        number: "Please enter a valid number",
                        min: "Discount amount cannot be negative"
                    },
                    "price_subtotal[]": {
                        number: "Enter a valid number",
                        min: "Subtotal must be zero or more"
                    },
                    "tax[]": {
                        required: "Tax is required",
                        number: "Enter a valid number",
                        min: "Tax must be zero or more"
                    },
                    "tax_amount[]": {
                        required: "Tax amount is required",
                        number: "Enter a valid number",
                        min: "Amount must be zero or more"
                    },
                    "product_total[]": {
                        required: "Product total is required",
                        number: "Enter a valid number",
                        min: "Total must be zero or more"
                    }
                },
                submitHandler: function (form) {
                    let imeiNumbers = [];

                    $('.imei-input').each(function () {
                        let val = $(this).val().trim();
                        if (val) imeiNumbers.push(val);
                    });


                    $.ajax({
                        url: "{{ route('check.imei-numbers') }}",
                        method: "POST",
                        data: {
                            imeiNumbers: imeiNumbers,
                            _token: $('meta[name="csrf-token"]').attr('content') // Make sure CSRF token is included
                        },
                        success: function (response) {
                            const $productReturned = $('#productReturned'); // Your message container

                            if (response.status === 200) {
                                $productReturned
                                    .text(response.message)
                                    .css('color', 'green')
                                    .show();

                                $('.error-message').text('').addClass('d-none').hide();
                                form.submit(); // Assuming `form` is your form element
                            } else if (response.status === 422) {
                                // let mainMessage = 'IMEI validation error';
                                let mainMessage ;
                                if (response.message) {
                                    mainMessage = response.message;
                                }

                                $productReturned
                                    .text(mainMessage)
                                    .addClass('text-danger')
                                    .show();

                                // .css('color', 'red')

                                // Show field-level error messages
                                $('.imei-input').each(function () {
                                    const value = $(this).val().trim();
                                    const wrapper = $(this).closest('.input-wrapper');
                                    const errorDiv = wrapper.find('.error-message');

                                    const issues = response.invalid_numbers[value];

                                    if (Array.isArray(issues) && issues.length > 0) {
                                        const messages = [];
                                        if (issues.includes('duplicate')) messages.push(`Duplicate IMEI ${value} found.`);
                                        if (issues.includes('return')) messages.push(`IMEI ${value} is a returned product.`);
                                        if (issues.includes('sold')) messages.push(`IMEI ${value} already exists in stock.`);

                                        errorDiv
                                            .html(messages.join('<br>'))
                                            .removeClass('d-none')
                                            .css('display', 'block');
                                    } else {
                                        errorDiv.text('').addClass('d-none').hide();
                                    }
                                });
                            } else {
                                alert('Something went wrong!');
                            }
                        },
                        error: function (xhr) {
                            console.error("Error:", xhr.responseText);
                        }
                    });



                },
                errorClass: "text-danger",
                errorElement: "span",
                highlight: function(element) {
                    $(element).addClass("is-invalid");
                },
                unhighlight: function(element) {
                    $(element).removeClass("is-invalid");
                }
            });
        });
    </script>
@endsection
