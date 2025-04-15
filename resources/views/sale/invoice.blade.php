<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Exact Invoice Layout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{--    <style>--}}
    {{--        .invoice-box {--}}
    {{--            border: 1px solid #000;--}}
    {{--            padding: 15px;--}}
    {{--        }--}}

    {{--        .table-bordered td, .table-bordered th {--}}
    {{--            border: 1px solid black !important;--}}
    {{--        }--}}

    {{--        .no-border td {--}}
    {{--            border: none !important;--}}
    {{--        }--}}

    {{--        .bold {--}}
    {{--            font-weight: bold;--}}
    {{--        }--}}
    {{--    </style>--}}
</head>
<body>
<div class="container invoice-box mt-4">

    <table class="table table-bordered mt-3 mb-0" style="border: 1px solid black" aria-colspan="5">
        <tr>
            <td><h3>Kushal Mobile & Computer</h3>
                <div>Address:- Near Hanuman Mandir , Akkalkuwa , Dist.Nandurbar-425415(M.S.)</div>
            </td>
            <td>GSTIN NO: 27AEIPJ3158A1ZT</td>

        </tr>
        <tr>
            <td class="mb-0 bold">TAX INVOICE</td>
            <td class="mb-0 bold">class Memo</td>

        </tr>

        <tr>
            <td><span class="bold">Invoice No :</span>
                {{$sale->invoice_no}}</td>
            <td><span class="bold">Invoice Date :</span>
                {{$sale->invoice_date}}</td>
        </tr>
        <tr>
            <td><span class="bold">Address :-</span> Vadodara
                <div><b>Phone no:</b> <span>7826867868 </span></div>
            <td><span class="bold">Payments Type :-</span> {{$sale->payment_method}}</td>


        </tr>

    </table>

    <table border="1" style="width:100%; border-collapse: collapse; font-size: 15px; margin: auto;">
        <thead>
        <tr>
            <th>Sr. No.</th>
            <th>Brand</th>
            <th>Model</th>
            <th>Colour</th>
            <th>IMEI No.</th>
            <th>Qty</th>
            <th>MRP</th>
            <th>Discount</th>
            <th>Net Amt</th>
        </tr>
        </thead>
        <tr>

        @foreach($sale->products as $index => $product)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>real me</td>
                <td>{{ $product->product_name }}</td>
                <td>BLACK</td>
                <td>0</td>
                <td>1</td>
                <td>{{ $product->price }}</td>
                <td>{{ $product->discount }}</td>
                <td>{{ $product->price_subtotal }}</td>
            </tr>
        <tr>
            <td colspan="8" style="text-align: right">Sub Total</td>
            <td>{{$sale->sub_total}}</td>
        </tr>
        @if($sale->tax_type === '1')
            <tr>
                <td colspan="8" style="text-align: right">CGST</td>
                <td>{{ number_format($product->tax_amount / 2, 2) }}</td>
            </tr>
            <tr>
                <td colspan="8" style="text-align: right">SGST</td>
                <td>{{ number_format($product->tax_amount / 2, 2) }}</td>
            </tr>
        @elseif($sale->tax_type === '2')
            <tr>
                <td colspan="8" style="text-align: right">IGST</td>
                <td>{{ number_format($product->tax_amount, 2) }}</td>
            </tr>
        @endif

        <th colspan="8" style="text-align: right">Grand Total</th>
        <th>{{$sale->total_amount}}</th>
        @endforeach
    </table>
</div>


</div>
</body>
</html>
