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
                <div>Address: Near Hanuman Mandir, Akkalkuwa, Dist.Nandurbar-425415(M.S.)</div>
            </td>
        </tr>

        <td class="mb-0 bold" style="text-align: center"><b>TAX INVOICE </b></td>
    </table>

    <table border="1" style="width:100%; border-collapse: collapse; font-size: 15px; margin: auto;">
        <tr>
            <td><span class="bold">Invoice No:</span>
                {{$sale->invoice_no ?? ''}}</td>
            <td><span class="bold">Invoice Date:</span>
                {{$sale->invoice_date ?? ''}}</td>
            <td>GSTIN No: 27AEIPJ3158A1ZT</td>
        </tr>

    </table>

    <table border="1" style="width:100%; border-collapse: collapse; font-size: 15px; margin: auto;">
        <tr>
            <td>
                <div><span class="bold">Customer Name: {{$sale->customer->name ?? ''}}</span></div>
                <div><span class="bold">Address: {{$sale->customer->city ?? ''}}</span></div>
                <div><span class="bold">Phone: {{$sale->customer->phone ?? ''}}</span></div>
            <td><span class="bold">Payment Type:</span> {{ config('constants.database_enum.sales.payment_method.name')[$sale->payment_method] ?? ''}}</td>
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
            {{--            <th>MRP (<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>)</th>--}}
            <th>Discount(%)</th>
            <th>Net Amount (<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>)</th>
        </tr>
        </thead>
        <tr>

        @foreach($sale->saleProducts as $index => $product)
            <tr>
                <td style="text-align: right">{{ $index + 1 }}</td>
                <td>{{$product->product->brand->name ?? ''}}</td>
                <td>{{$product->product->product_name ?? '' }}</td>
                <td>{{$product->purchaseProduct->color ?? ''}}</td>
                <td style="text-align: right">{{$product->purchaseProduct->imei ?? ''}}</td>
                {{--                <td style="text-align: right">{{ $product->price }}</td>--}}
                <td style="text-align: right">{{ $product->discount ?? ''}}</td>
                <td style="text-align: right">{{ $product->price_subtotal ?? ''}}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="6" style="text-align: right">Sub Total</td>
            <td style="text-align: right">{{$sale->sub_total ?? '-'}}</td>
        </tr>
        @php
            $taxPercent     = optional($sale->saleProducts->first())->tax; // assume same tax % for all lines
        @endphp
        @if($sale->tax_type === '1')
            <tr>
                <td colspan="6" style="text-align: right">CGST({{$taxPercent/2}}%)</td>
                <td style="text-align: right">{{ number_format($product->tax_amount / 2, 2) ?? ''}}</td>
            </tr>
            <tr>
                <td colspan="6" style="text-align: right">SGST({{$taxPercent/2}}%)</td>
                <td style="text-align: right">{{ number_format($product->tax_amount / 2, 2) ?? ''}}</td>
            </tr>
        @elseif($sale->tax_type === '2')
            <tr>
                <td colspan="6" style="text-align: right">IGST({{$taxPercent}}%)</td>
                <td style="text-align: right">{{ number_format($product->tax_amount, 2) ?? ''}}</td>
            </tr>
        @endif
        @php
            $rounded = round($sale->total_amount);
            $difference = $sale->total_amount - $rounded;
        @endphp

        @if($difference != 0)
            <tr style="text-align: right">
                <td colspan="6">
                    @if($difference < 0)
                        Round Up by
                    @elseif($difference > 0)
                        Rounded Down by
                    @endif
                </td>
                <td>
                    @if($difference < 0)
                        {{ number_format(abs($difference), 2) }}
                    @elseif($difference > 0)
                        {{ number_format($difference, 2) }}
                    @endif
                </td>

            </tr>
        @endif

        <th colspan="6" style="text-align: right">Grand Total</th>
        <th style="text-align: right">{{ number_format($rounded, 2) ?? ''}}</th>
    </table>

    <table border="1" style="width:100%; border-collapse: collapse; font-size: 15px;">
        <tr>
            <td style="text-align: left; padding: 10px; font-size: 14px;">
                <strong>Terms & Conditions</strong>
                <ol style="padding-left: 20px; margin: 0;">
                    <li>All disputes are subject to Nandurbar Jurisdiction.</li>
                    <li>Goods once sold cannot be taken back or exchanged.</li>
                    <li>For service repair, contact respective service centres.</li>
                    <li>We declare that the invoice shows the actual price of the goods described and that all particulars are true and correct.</li>
                    <li>Any manufacturing defects are to be entertained by the authorized service center only.</li>
                    <li>Kushal Mobile is not responsible for any kind of water damage and physical damage.</li>
                    <li>Warranty for mobile is valid for 1 year and accessories for 6 months.</li>
                    <li>Misuse/water damage/breakage is not covered under warranty.</li>
                    <li>No warranty or guarantee on case, back cover, toughened glass, and screen guard.</li>
                    <li>Once a bill is generated, it will not be amended.</li>
                    <li>Please note that GST number will not be added after the bill is saved.</li>
                </ol>
            </td>
        </tr>

    </table>

    <table border="1" style="width:100%; border-collapse: collapse; font-size: 15px; margin: auto;">
        <tr>
            <td style="width: 200px; text-align: center; ">
                <img src="{{ asset('public/backend/assets/img/qrcode.jpeg') }}" alt="Invoice QR Code" width="200" height="180">
            </td>
            <td style="padding: 10px; vertical-align: top;">
                <span class="bold">Bank Details:</span>
                <p>
                    Bank Account details <br/>
                    Kushal Mobile and Computers <br/>
                    HDFC BANK <br/>
                    A/C NO-50200012647776 <br/>
                    IFC CODE-HDFC0004099
                </p>
            </td>
        </tr>
    </table>

    <table  border="1"  style="width:100%; border-collapse: collapse; font-size: 15px; margin: auto;">
        <tr>
            <td style="width: 50%; text-align: left; padding-top: 40px;">
               <br>
                Receivers Signature
            </td>
            <td style="width: 50%; text-align: right; padding-top: 5px;">
                for <strong> Kushal Mobile and Computers</strong><br><br>
               <br>
                Authorized Signatory
            </td>
        </tr>
    </table>

</div>

@if($sale->payment_method == "2")
    <div style="page-break-after: always;"></div>
    <div class="container invoice-box mt-4">
        <h3>Deduction chart</h3>
        <table border="1" style="width:100%; border-collapse: collapse; font-size: 15px; margin: auto;">
            <thead>
            <tr>
                <th>Sr No</th>
                <th>Deduction Date</th>
                <th>Status</th>
                <th>Remaining Value </br>(<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>)</th>
                <th>EMI Value </br>(<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>)</th>
                <th>Paid Value </br>( <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>)</th>
                <th>Paid Date</th>
                <th>Payment Mode</th>
                <th>Transaction Id</th>
            </tr>
            </thead>
            <tbody>
            @foreach($deductions as $row)
                <tr>
                    <td style="text-align: right">{{$loop->iteration}}</td>
                    <td style="text-align: center">{{ \Carbon\Carbon::parse($row->emi_date)->format('d.m.y') ?? '' }}</td>
                    <td style="text-align: center">{{ \Illuminate\Support\Str::studly($row->status) ?? '' }}</td>
                    <td style="text-align: right">{{$row->remaining ?? ''}}</td>
                    <td style="text-align: right">{{$row->finance->emi_value ?? ''}}</td>
                    <td style="text-align: right">{{$row->emi_value_paid ?? ''}}</td>
                    <td style="text-align: center">{{ \Carbon\Carbon::parse( $row['paid_date'])->format('d.m.y') ?? '-' }}</td>
                    <td style="text-align: center">{{ config('constants.database_enum.deductions.payment_mode.name') [(int)$row->payment_mode] ?? '-'}}</td>
                    <td style="text-align: right">{{$row->refernce_no ?? '' }}  </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif
</body>
</html>
