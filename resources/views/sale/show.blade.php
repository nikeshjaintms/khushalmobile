@extends('layouts.app')
@section('title', 'Admin Panel')
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
                    <a href="{{ route('admin.product.index') }}">Sale</a>
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
                        <a href="{{ route('admin.sale.index') }}" class="btn btn-rounded btn-primary float-end"> <i
                                class="fas fa-angle-left"></i> Back</a>
                        <h4 class="card-title">Sale Detailed</h4>
                    </div>
                    <div class="card-body">

                        <div class="d-flex row">
                        <div class="col-md-4">
                                <span class="fw-bold" style="font-size: 15px">Customer Name : </span>
                                <span style="font-size: 15px">{{$sale->customer->name}}</span>
                            </div>
                            <div class="col-md-4">
                                <span class="fw-bold" style="font-size: 15px">Invoice No : </span>
                                <span style="font-size: 15px">{{$sale->invoice_no}}</span>
                            </div>
                            <div class="col-md-4">
                                <span class="fw-bold" style="font-size: 15px">Invoice Date :</span>
                                <span style="font-size: 15px">{{$sale->invoice_date}}</span>
                            </div>

                        </div>
                        <br>
                        <br>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Sr. No.</th>
                                        <th>Brand</th>
                                        <th>Model</th>
                                        <th>Colour</th>
                                        <th>IMEI No.</th>
                                        <th>MRP</th>
                                        <th>Discount</th>
                                        <th>Discount Amount</th>
                                        <th>Price Sub Total</th>
                                        <th>Tax</th>
                                        <th>Tax Amount</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sale->saleProducts as $index => $product)

                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{$product->product->brand->name}}</td>
                                        <td>{{$product->product->product_name }}</td>
                                        <td>{{$product->purchaseProduct->color}}</td>
                                        <td>{{$product->purchaseProduct->imei}}</td>
                                        <td>{{ $product->price }}</td>
                                        <td>{{ $product->discount }}</td>
                                        <td>{{ $product->discount_amount }}</td>
                                        <td>{{ $product->price_subtotal }}</td>
                                        <td>{{ $product->tax }}</td>
                                        <td>{{ $product->tax_amount }}</td>
                                        <td>{{ $product->price_total }}</td>
                                    </tr>
                                    @endforeach

                                    <tr>
                                        <th colspan="11" style="text-align: right">Sub Total</th>
                                        <td>{{ $sale->sub_total }}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="11" style="text-align: right">Tax Type </th>
                                        <td>  {{ config('constants.database_enum.sales.tax_type.name')[$sale->tax_type] }}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="11" style="text-align: right">Total Tax Amount</th>
                                        <td>{{ $sale->total_tax_amount }}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="11" style="text-align: right">Payment Method</th>
                                        <td>  {{ config('constants.database_enum.sales.payment_method.name')[$sale->payment_method] }}</td>
                                    </tr>
                                    @if($sale->payment_method === '1')
                                    {!!  $selectedRefer ? '<tr>
                                            <th colspan="11" style="text-align: right">Reference Number</th>
                                            <td>{{$selectedRefer }}</td>
                                        </tr>' : ''!!}
                                     @elseif($sale->payment_method === '3')
                                        <tr>
                                            <th colspan="11" style="text-align: right">Down Payment</th>
                                            <td>{{$finance->downpayment }}</td>
                                        </tr>
                                        <tr>
                                            <th colspan="11" style="text-align: right">Processing Fee</th>
                                            <td>{{$finance->processing_fee }}</td>
                                        </tr>
                                        <tr>
                                            <th colspan="11" style="text-align: right">EMI Charger</th>
                                            <td>{{$finance->emi_charger }}</td>
                                        </tr>
                                        <tr>
                                            <th colspan="11" style="text-align: right">Finance Amount</th>
                                            <td>{{$finance->finance_amount }}</td>
                                        </tr>
                                        <tr>
                                            <th colspan="11" style="text-align: right">Month Duration</th>
                                            <td>{{$finance->month_duration }}</td>
                                        </tr>
                                        <tr>
                                            <th colspan="11" style="text-align: right">EMI Value</th>
                                            <td>{{$finance->emi_value }}</td>
                                        </tr>
                                        <tr>
                                            <th colspan="11" style="text-align: right">Penalty</th>
                                            <td>{{$finance->penalty }}</td>
                                        </tr>
                                        <tr>
                                            <th colspan="11" style="text-align: right">Dedication Date</th>
                                            <td>{{$finance->dedication_date }}</td>
                                        </tr>
                                        <tr>
                                            <th colspan="11" style="text-align: right">Finance year</th>
                                            <td>{{$finance->finance_year }}</td>
                                        </tr>
                                        <tr>
                                            <th colspan="11" style="text-align: right">Status</th>
                                            <td>{{$finance->status }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th colspan="11" style="text-align: right">Grand Total</th>
                                        <td>{{ $sale->total_amount }}</td>
                                    </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endsection
