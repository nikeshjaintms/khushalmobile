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
        <tr>
            <td style="text-align: center"><h3>Kushal Mobile & Computer</h3>
                <div>Address:- Near Hanuman Mandir , Akkalkuwa , Dist.Nandurbar-425415(M.S.)</div>
            </td>
        </tr>

        <td class="mb-0 bold" style="text-align: center"><b>TAX INVOICE </b></td>
    </table>

    <table border="1" style="width:100%; border-collapse: collapse; font-size: 15px; margin: auto;">
        <tr>
            <td><span class="bold">Invoice No :</span>
                {{$sale->invoice_no}}</td>
            <td><span class="bold">Invoice Date :</span>
                {{$sale->invoice_date}}</td>
            <td>GSTIN NO: 27AEIPJ3158A1ZT</td>
        </tr>

    </table>

    <table border="1" style="width:100%; border-collapse: collapse; font-size: 15px; margin: auto;">
        <tr>
            <td>
                <div><span class="bold">Customer Name: {{$sale->customer->name}}</span></div>
                <div><span class="bold">Address: {{$sale->customer->address}}</span></div>
                <div><span class="bold">Phone: {{$sale->customer->phone}}</span></div>
            <td><span class="bold">Payments Type :-</span>  {{ config('constants.database_enum.sales.payment_method.name')[$sale->payment_method] }}</td>


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
            <th>MRP</th>
            <th>Discount</th>
            <th>Net Amt</th>
        </tr>
        </thead>
        <tr>

        @foreach($sale->saleProducts as $index => $product)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{$product->product->brand->name}}</td>
                <td>{{$product->product->product_name }}</td>
                <td>{{$product->purchaseProduct->color}}</td>
                <td>{{$product->purchaseProduct->imei}}</td>
                <td>{{ $product->price }}</td>
                <td>{{ $product->discount }}</td>
                <td>{{ $product->price_subtotal }}</td>
            </tr>
            <tr>
                <td colspan="7" style="text-align: right">Sub Total</td>
                <td>{{$sale->sub_total}}</td>
            </tr>
            @if($sale->tax_type === '1')
                <tr>
                    <td colspan="7" style="text-align: right">CGST</td>
                    <td>{{ number_format($product->tax_amount / 2, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="7" style="text-align: right">SGST</td>
                    <td>{{ number_format($product->tax_amount / 2, 2) }}</td>
                </tr>
            @elseif($sale->tax_type === '2')
                <tr>
                    <td colspan="7" style="text-align: right">IGST</td>
                    <td>{{ number_format($product->tax_amount, 2) }}</td>
                </tr>
            @endif

            <th colspan="7" style="text-align: right">Grand Total</th>
            <th>{{$sale->total_amount}}</th>
        @endforeach
    </table>
</div>


</div>
</body>
</html>
