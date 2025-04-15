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
                                            <div>
                                                <label for="">Customer<span style="color: red">*</span></label>
                                                <select class="form-select customer" aria-label="Default select example" name="customer_id">
                                                    <option selected> Select Customer</option>
                                                    @foreach($customers as $item)
<<<<<<< HEAD
                                                        <option value="{{$item->id}}">{{$item->name}}</option>
=======
                                                        <option value="{{$item->id}}" {{ $selectedCustomer->contains($item->id) ? 'selected' : '' }}>{{$item->name}}</option>
>>>>>>> 08df154bee548363a248537b1f25d92ee3c15439
                                                    @endforeach
                                                    @error('customer') <p style="color: red;">{{ $message }}</p> @enderror
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
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

                                                            {{--                                                                @foreach($brands as $brand)--}}
                                                            {{--                                                                    <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }} {{ $selectedBrand->contains($brand->id) ? 'selected' : '' }}>--}}
                                                            {{--                                                                        {{ $brand->name }}--}}
                                                            {{--                                                                    </option>--}}
                                                            {{--                                                                @endforeach--}}
                                                            @error('brand_id') <p style="color: red;">{{ $message }}</p> @enderror
                                                        </select>
                                                    </td>
                                                    <td>
{{--                                                        <select id="product" class="form-control form-select product-name product-select product" name="products[0][product_id]">--}}
{{--                                                            <option value=""> Select Product</option>--}}
{{--                                                            @foreach($products as $product)--}}
{{--                                                                <option value="{{ $product->id }}">{{ $product->name }}</option>--}}
{{--                                                            @endforeach--}}
{{--                                                        </select>--}}
                                                        <select id="product" class="form-control form-select product-name product-select product" name="products[0][product_id]">
                                                            <option value="">Select Product</option>
                                                            @foreach($products as $product)
                                                                <option value="{{ $product->id }}" {{ $product->id == $selectedProductId ? 'selected' : '' }}>
                                                                    {{ $product->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                        @error('product_id') <p style="color: red;">{{ $message }}</p> @enderror
                                                    </td>

                                                    <td>
                                                        <select class="form-control form-select imi-no" name="imiNo" aria-label="Default select example" required>
                                                            <option selected> Select Imi No</option>
                                                        </select>
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
                                                    </select>
                                                    @error('payment_method') <p style="color: red;">{{ $message }}</p> @enderror
                                                </div>
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
                    phone: {
                        required: true
                    },

                    address: {
                        required: true
                    },
                    city: {
                        required: true
                    }

                },
                messages: {
                    name: {
                        required: "Please Enter Dealer Name",
                        minleght: "Please Enter Minimum 3 Characters",
                        maxlength: "Please Enter Maximum 50 Characters",

                    },
                    phone: {
                        required: "Please enter phone number"
                    },
                    address: {
                        required: "Please enter address"
                    },
                    city: {
                        required: "please enter city"
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
