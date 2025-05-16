@extends('layouts.app')
{{-- @if(Auth::guard('admin')->check()) --}}
@section('title','Admin Panel')

{{-- @endif --}}

@section('content-page')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Sale Report</h3>
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
                        <a href="#">Sale Report</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Sale Report</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Sale Date</th>
                                        <th>Customer</th>
                                        <th>Mobile Number</th>
                                        <th>Brand Name</th>
                                        <th>Product Name</th>
                                        <th>Price</th>
                                        <th>Payment Method</th>

{{--                                        <th>Invoice No</th>--}}
{{--                                        <th>Discount</th>--}}
{{--                                        <th>Total</th>--}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($sales as $sale)
                                        <tr>
                                            <td>{{$loop ->iteration}}</td>
                                            <td>{{ $sale->invoice_date }}</td>
                                            <td>{{$sale->customer->name}}</td>
                                            <td>{{$sale->customer->phone}}</td>
                                            <td>
                                                @foreach ($sale->saleProducts as $index => $product)
                                                    {{ $product->product->brand->name ?? '-' }}@if (!$loop->last)
                                                        ,
                                                    @else

                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($sale->saleProducts as $index => $product)
                                                    {{ $product->product->product_name ?? '-' }}@if (!$loop->last)
                                                        ,
                                                    @else

                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                        @foreach ($sale->saleProducts as $product)
                                                {{ $product->price ?? '-' }}@if (!$loop->last)
                                                    ,
                                                @else

                                                @endif
{{--                                                <td>{{ $product->price }}</td>--}}
                                                {{--                                                <td>{{ $product->discount_amount}}</td>--}}
                                                {{--                                                <td>{{ $product->price_total }}</td>--}}
                                            @endforeach
                                            </td>
                                            <td>{{ config('constants.database_enum.sales.payment_method.name') [$sale->payment_method] }}</td>
{{--                                            <td>{{ $sale->invoice_no }}</td>--}}
                                        </tr>
                                    @endforeach
{{--                                   <th scope="row" colspan="7">Final Total</th>--}}
{{--                                    <td>--}}
{{--                                       {{$totalAmount}}--}}
{{--                                    </td>--}}
                                    </tbody>

                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
@endsection
