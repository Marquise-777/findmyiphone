<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            width: 180mm;
            margin: 0 auto;
        }

        .center {
            text-align: center;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        table {
            width: 100%;
            font-size: 12px;
        }

        table th, table td {
            text-align: left;
            padding: 4px 0;
        }

        .right {
            text-align: right;
        }

        @media print {
            body {
                width: 80mm;
            }
        }
    </style>
</head>
<body onload="window.print()">

<div class="center">
    <h3>BILL / CASH MEMO</h3>
    <h2>FIND MY iPHONE</h2>
    <p>
        Licence : AMC-12/12732/2022<br>
        Dawrpui, Aizawl, Mizoram - 796001<br>
        Contact - 8787645136
    </p>
</div>

<div class="divider"></div>

<p>
    Invoice: {{ $order->invoice_number }}<br>
    Date: {{ $order->created_at->format('d-m-Y H:i') }}<br>
    Customer: {{ $order->customer_name }}
</p>

<div class="divider"></div>

<table>
    <thead>
        <tr>
            <th>Item</th>
            <th class="right">Qty</th>
            <th class="right">Price</th>
            <th class="right">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td>
                {{ $item->product->name }}<br>
                <small>IMEI/Serial: {{ $item->imeiorserial }}</small>
            </td>
            <td class="right">{{ $item->quantity }}</td>
            <td class="right">{{ number_format($item->price, 2) }}</td>
            <td class="right">{{ number_format($item->total, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="divider"></div>

<table>
    <tr>
        <td>Subtotal</td>
        <td class="right">{{ number_format($order->subtotal, 2) }}</td>
    </tr>
    <tr>
        <td>Discount</td>
        <td class="right">{{ number_format($order->discount, 2) }}</td>
    </tr>
    <tr>
        <td>Tax</td>
        <td class="right">{{ number_format($order->tax, 2) }}</td>
    </tr>
    <tr>
        <td><strong>Total</strong></td>
        <td class="right"><strong>{{ number_format($order->total, 2) }}</strong></td>
    </tr>
    <tr>
        <td>Paid</td>
        <td class="right">{{ number_format($order->paid, 2) }}</td>
    </tr>
    <tr>
        <td>Due</td>
        <td class="right">{{ number_format($order->due, 2) }}</td>
    </tr>
</table>

<div class="divider"></div>

<p>
• 1 month warranty<br>
• LCD leh mahni tihchhiat a huam lo<br>
• Memo tello in warranty a claim theihloh<br>
• Siam theihloh anih loh chuan Exchange tawp a theih ngawt loh
</p>

<div class="center">
    <p>Thank You! Visit Again</p>
</div>

</body>
</html>
