@extends('layouts.app')
@section('title', 'Admin Panel')
@section('content-page')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Purchase</h3>
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
                        <a href="{{ route('admin.product.index') }}">Purchase</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Detailed</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('admin.purchase.index') }}" class="btn btn-rounded btn-primary float-end"> <i
                                    class="fas fa-angle-left"></i> Back</a>
                            <h4 class="card-title">Purchase Detailed</h4>
                        </div>
                        <div class="card-body">

                                <div class="d-flex row">
                                    <div class="col-md-4">
                                        <span class="fw-bold" style="font-size: 15px">Dealer: </span>
                                        <span style="font-size: 15px">{{ $purchases->name }}</span>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="fw-bold" style="font-size: 15px">Purchase No:</span>
                                        <span style="font-size: 15px">{{ $purchases->po_no }}</span>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="fw-bold" style="font-size: 15px">Purchase Date:</span>
                                        <span style="font-size: 15px">{{ $purchases->po_date }}</span>
                                    </div>
                                </div>
                                <br>
                                <br>
                                <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Brand</th>
                                            <th>Product</th>
                                            <th>Color</th>
                                            <th>IMEI</th>
                                            <th>Price</th>
                                            <th>Discount</th>
                                            <th>SubTotal</th>
                                            <th>Tax</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($purchase_products as $product)
                                            <tr>
                                                <td>{{ $product->brand }}</td>
                                                <td>{{ $product->product_name }}</td>
                                                <td>{{ $product->color }}</td>
                                                <td>{{ $product->imei }}</td>
                                                <td>{{ $product->price }}</td>
                                                <td>{{ $product->discount }}</td>
                                                <td>{{ $product->price_subtotal }}</td>
                                                <td>{{ $product->tax }}</td>
                                                <td>{{ $product->product_total }}</td>
                                            </tr>
                                        @endforeach

                                    <tr>
                                        <th colspan="8" style="text-align: right">Sub Total</th>
                                        <td>{{ $purchases->sub_total }}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="8" style="text-align: right">GST </th>
                                        <td>{{ Str::upper($purchases->tax_type) }}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="8" style="text-align: right">Total Tax Amount</th>
                                        <td>{{ $purchases->total_tax_amount }}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="8" style="text-align: right">Grand Total</th>
                                        <td>{{ $purchases->total }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

 @endsection
