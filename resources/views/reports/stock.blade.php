@extends('layouts.app')
{{-- @if(Auth::guard('admin')->check()) --}}
@section('title','Admin Panel')

{{-- @endif --}}
@section('content-page')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/select2.min.css')}}" />
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Stock Report</h3>
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
                        <a href="#">Stock Report</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Stock Report</h4>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('admin.stock.invoice') }}" target="_blank">
                                <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="">Brand Name<span style="color: red">*</span></label>
                                                                    <select name="brand_id" id="brandId" class="form-control brandSelect2">
                                                                        <option value="">Select Brand</option>
                                                                        @foreach ($brands as $brand)
                                                                            <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                                                                {{ $brand->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="">Product Name<span style="color: red">*</span></label>
                                                                    <select name="product_id" id="productId" class="form-control productSelect2">
                                                                        <option value="">Select Product</option>
                                                                        @foreach ($productSearch as $product)
                                                                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                                                                {{ $product->product_name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                    <div class="col-md-4">
                                        <div class="form-group p-4">
                                            <button type="submit" class="btn btn-primary">Generate PDF</button>
                                        </div>
                                    </div>

                                </div>
                            </form>

                    </div>
                            <div class="table-responsive card-header">
                                <table id="basic-datatables" class="display table table-striped table-hover stockTable">
                                    <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Brand Name</th>
                                        <th>Product Name</th>
                                        <th>Qty</th>
                                        <th>View</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($products as $item)
                                        <tr data-brand-id="{{ $item['brand_id'] }}" data-product-id="{{ $item['product_id'] }}">
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{ $item['brand_name'] }}</td>
                                            <td>{{ $item['product_name'] }}</td>
                                            <td>{{ $item['null_status_and_invoice'] }}</td>
                                            <td>
                                                <a href="{{ route('admin.stock.show',  $item['id']) }}" class="btn btn-lg btn-link btn-primary">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
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
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="{{ asset('backend/assets/js/select2.min.js')}}"></script>


    <script>
    $(document).ready(function() {
        // $('.js-example-basic-single').select2();
        $('.brandSelect2').select2();
        $('.productSelect2').select2();
    });
    // $(document).ready(function() {
    //
    //
    //     $('#productId').on('change', function () {
    //         var selectedProductId = $(this).val();
    //
    //         $('#basic-datatables tbody tr').each(function () {
    //             var rowProductId = $(this).find('td:eq(1)').text() // or use .find('td:eq(1)').text()
    //
    //             if (!selectedProductId || rowProductId == selectedProductId) {
    //                 $(this).show();
    //             } else {
    //                 $(this).hide();
    //             }
    //         });
    //     });
    //
    //
    //
    //     $('#brandId').on('change', function () {
    //     var brandId = $(this).val();
    //     if (brandId) {
    //         $.ajax({
    //             url: '/admin/get-products-by-brand/' + brandId,
    //             type: 'GET',
    //             success: function (data) {
    //                 $('#productId').empty().append('<option value="">Select Product</option>');
    //                 $.each(data, function (key, product) {
    //                     $('#productId').append('<option value="' + product.id + '">' + product.product_name + '</option>');
    //                 });
    //                 $('#productId').trigger('change');
    //             }
    //         });
    //     } else {
    //         $('#productId').empty().append('<option value="">Select Product</option>');
    //     }
    // });
    //
    // });
    function filterTable() {
        var selectedBrandId = $('#brandId').val();
        var selectedProductId = $('#productId').val();

        $('#basic-datatables tbody tr').each(function () {
            var brandId = $(this).data('brand-id').toString();
            var productId = $(this).data('product-id').toString();

            var brandMatch = !selectedBrandId || brandId === selectedBrandId;
            var productMatch = !selectedProductId || productId === selectedProductId;

            if (brandMatch && productMatch) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    $('#brandId').on('change', function () {
        var brandId = $(this).val();
        if (brandId) {
            $.ajax({
                url: '/admin/get-products-by-brand/' + brandId,
                type: 'GET',
                success: function (data) {
                    $('#productId').empty().append('<option value="">Select Product</option>');
                    $.each(data, function (key, product) {
                        $('#productId').append('<option value="' + product.id + '">' + product.product_name + '</option>');
                    });
                    $('#productId').val('').trigger('change');
                    filterTable(); // Filter table after resetting product select
                }
            });
        } else {
            $('#productId').empty().append('<option value="">Select Product</option>');
            filterTable();
        }
    });

    $('#productId').on('change', function () {
        filterTable();
    });

</script>



@endsection
