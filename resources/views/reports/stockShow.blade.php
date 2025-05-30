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
                                        <th>Sr No</th>
                                        <th>Dealer Name</th>
                                        <th>Purchase No</th>
                                        <th>Purchase Date</th>
                                        <th>IMEI Number</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($stockData as $index=> $item)
                                        <tr>
                                            <td>{{$index+1}}</td>
                                            <td>{{ $item->purchase->dealer->name ?? 'N/A' }}</td>
                                            <td>{{ $item->purchase->po_no ?? 'N/A'}}</td>
{{--                                            <td>{{ \Carbon\Carbon::parse($item->purchase->po_date)->format('d-m-Y') ?? '-'}}</td>--}}
                                            <td>
                                                {{ optional($item->purchase)->po_date
                                                    ? \Carbon\Carbon::parse($item->purchase->po_date)->format('d-m-Y')
                                                    : 'N/A' }}
                                            </td>

                                            <td>{{ $item->imei ?? 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No available stock found.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection
