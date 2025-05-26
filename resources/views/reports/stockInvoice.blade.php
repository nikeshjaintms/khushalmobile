<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Exact Invoice Layout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container invoice-box mt-4">

    <table border="1" style="width:100%; border-collapse: collapse; font-size: 15px; margin: auto;">
        <thead>
        <th colspan="4">Stock Chart</th>
        <tr>
            <th>Sr No</th>
{{--            <th>Purchase No</th>--}}
{{--            <th>Purchase Date</th>--}}
            <th>Brand Name</th>
            <th>Product Name</th>
            <th>Qty</th>

        </tr>
        </thead>
        <tbody>

        @foreach($products as $row)

            <tr>
                <td style="text-align: center" >{{$loop->iteration}}</td>
{{--                <td>{{ $pp->purchase->po_no ?? 'N/A' }}</td>--}}
{{--                <td>{{ $pp->purchase->po_date ?? 'N/A' }}</td>--}}
                <td style="text-align: center">{{$row->brand->name ?? ''}}</td>
                <td style="text-align: center">{{$row->product_name ?? ''}}</td>
                <td style="text-align: center"> {{ $purchaseProducts->firstWhere('product_id', $row->id)->total ?? 0 }}</td>

            </tr>
        @endforeach
        </tbody>
    </table>
{{--    <table border="1" style="width:100%; border-collapse: collapse; font-size: 15px; margin: auto;">--}}
{{--        <thead>--}}
{{--        <tr>--}}
{{--            <th>Sr No</th>--}}
{{--            <th>Purchase No</th>--}}
{{--            <th>Purchase Date</th>--}}
{{--            <th>Brand Name</th>--}}
{{--            <th>Product Name</th>--}}
{{--            <th>Qty</th>--}}
{{--        </tr>--}}
{{--        </thead>--}}
{{--        <tbody>--}}
{{--        @php--}}
{{--            $totalQty = 0;--}}
{{--        @endphp--}}


{{--        @php $sr = 1; @endphp--}}
{{--        @foreach ($products as $product)--}}
{{--            @foreach ($product->purchaseProducts as $pp)--}}
{{--                @if (is_null($pp->invoice_id) && is_null($pp->status))--}}
{{--                    @php--}}
{{--                        $totalQty += $pp->quantity;--}}
{{--                    @endphp--}}
{{--                @endif--}}
{{--                @if ( is_null($pp->invoice_id)--}}
{{--                        && is_null($pp->status)--}}
{{--                        )--}}
{{--                    <tr>--}}
{{--                        <td style="text-align: right">{{ $sr++ }}</td>--}}
{{--                        <td>{{ $pp->purchase->po_no ?? 'N/A' }}</td>--}}

{{--                        <td>--}}
{{--                            {{ optional($pp->purchase)->po_date--}}
{{--                                ? \Carbon\Carbon::parse($pp->purchase->po_date)->format('d-m-Y')--}}
{{--                                : 'N/A' }}--}}
{{--                        </td>--}}
{{--<td></td>--}}
{{--<td></td>--}}
{{--                        <td>{{ $product->brand->name ?? 'N/A' }}</td>--}}
{{--                        <td>{{ $product->product_name ?? 'N/A' }}</td>--}}
{{--                        <td style="text-align: right">{{ $pp->quantity }}</td>--}}
{{--                        <td> {{ $totalQty }}</td>--}}
{{--                    </tr>--}}
{{--                @endif--}}
{{--            @endforeach--}}
{{--        @endforeach--}}

{{--        @if($sr === 1)--}}
{{--            <tr>--}}
{{--                <td colspan="6" style="text-align: center;">No matching purchase records found.</td>--}}
{{--            </tr>--}}
{{--        @endif--}}
{{--        </tbody>--}}
{{--    </table>--}}


</div>
</body>
</html>
