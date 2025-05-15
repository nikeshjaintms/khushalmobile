@extends('layouts.app')
@section('title', 'Admin Panel')
@section('content-page')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Stock View</h3>
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
                        <a href="{{ route('admin.purchase.index') }}">Stock View</a>
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
                            <a href="{{ route('admin.report.stock') }}" class="btn btn-rounded btn-primary float-end"> <i
                                    class="fas fa-angle-left"></i> Back</a>
                            <h4 class="card-title">Stock View Details</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>Dealer Name</th>
                                        <th>Purchase No</th>
                                        <th>Purchase Date</th>
                                        <th>IMEI Number</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($stockData as $item)
                                        <tr>
                                            <td>{{ $item->purchase->dealer->name ?? 'N/A' }}</td>
                                            <td>{{ $item->purchase->po_no }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->purchase->po_date)->format('d-m-Y') }}</td>
                                            <td>{{ $item->imei }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No available stock found.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
{{--                                <table class="table table-striped table-hover">--}}
{{--                                    <thead>--}}
{{--                                    <tr>--}}
{{--                                        <th>Brand</th>--}}
{{--                                        <th>Product</th>--}}
{{--                                        <th>Color</th>--}}
{{--                                        <th>IMEI</th>--}}
{{--                                        <th>Price</th>--}}
{{--                                        <th>Discount</th>--}}
{{--                                        <th>SubTotal</th>--}}
{{--                                        <th>Tax</th>--}}
{{--                                        <th>Total</th>--}}
{{--                                    </tr>--}}
{{--                                    </thead>--}}
{{--                                    <tbody>--}}
{{--                                    @foreach ($product as $item)--}}
{{--                                        <tr>--}}
{{--                                            <td>{{ $item->brand }}</td>--}}
{{--                                            <td>{{ $item->product_name }}</td>--}}
{{--                                            <td>{{ $item->color }}</td>--}}
{{--                                            <td>{{ $item->imei }}</td>--}}
{{--                                            <td>{{ $item->price }}</td>--}}
{{--                                            <td>{{ $item->discount }}</td>--}}
{{--                                            <td>{{ $item->price_subtotal }}</td>--}}
{{--                                            <td>{{ $item->tax }}</td>--}}
{{--                                            <td>{{ $item->product_total }}</td>--}}
{{--                                        </tr>--}}
{{--                                    @endforeach--}}

{{--                                    <tr>--}}
{{--                                        <th colspan="8" style="text-align: right">Sub Total</th>--}}
{{--                                        <td>{{ $purchases->sub_total }}</td>--}}
{{--                                    </tr>--}}
{{--                                    <tr>--}}
{{--                                        <th colspan="8" style="text-align: right">GST </th>--}}
{{--                                        <td>{{ Str::upper($purchases->tax_type) }}</td>--}}
{{--                                    </tr>--}}
{{--                                    <tr>--}}
{{--                                        <th colspan="8" style="text-align: right">Total Tax Amount</th>--}}
{{--                                        <td>{{ $purchases->total_tax_amount }}</td>--}}
{{--                                    </tr>--}}
{{--                                    <tr>--}}
{{--                                        <th colspan="8" style="text-align: right">Grand Total</th>--}}
{{--                                        <td>{{ $purchases->total }}</td>--}}
{{--                                    </tr>--}}
{{--                                </table>--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection
