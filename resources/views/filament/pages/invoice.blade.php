<x-filament-panels::page>
    <style>
        .custom-invoice * { all: revert; box-sizing: border-box; }
        .custom-invoice {
            max-width: 800px; margin: 0 auto; background: white; color: black;
            font-family: 'Courier New', Courier, monospace; padding: 20px;
            border: 1px solid #ccc; box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        @media print {
            body { margin: 0; padding: 0; }
            .custom-invoice { margin: 0; padding: 0; border: none; box-shadow: none; }
            .no-print { display: none; }
            .fi-sidebar,
            .fi-topbar,
            [data-testid="topbar"],
            .fi-header,
            nav { display: none !important; }
        }
        .invoice-header { text-align: center; border-bottom: 2px dashed #333; margin-bottom: 20px; padding-bottom: 10px; }
        .invoice-title { font-size: 24px; font-weight: bold; letter-spacing: 2px; }
        .invoice-sub { font-size: 12px; color: #555; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; }
        .info-label { font-weight: bold; }
        .items-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .items-table th, .items-table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .items-table th { background: #f2f2f2; font-weight: bold; }
        .summary-row { display: flex; justify-content: space-between; margin: 5px 0; font-size: 14px; }
        .total-row { text-align: right; font-weight: bold; font-size: 18px; margin-top: 20px; padding-top: 10px; border-top: 1px solid #333; }
        .print-button { display: inline-block; background: #007bff; color: white; padding: 8px 16px; border-radius: 4px; cursor: pointer; margin-top: 20px; border: none; }
    </style>

    <div class="custom-invoice">
        <div class="invoice-header">
            <div class="center">
                <img src="{{ asset('/logo.jpeg') }}" alt="Logo" style="max-width: 50px; border-radius: 50%;">
                <h3>BILL / CASH MEMO</h3>
                <h2>FIND MY iPHONE</h2>
                <p>
                    Licence : AMC-12/12732/2022<br>
                    Dawrpui, Aizawl, Mizoram - 796001<br>
                    Contact - 8787645136
                </p>
            </div>
        </div>

        <div>
            <div class="info-row">
                <span><span class="info-label">Customer:</span> 
                    {{ $this->order->customer->name ?? 'Walk-in' }}
                </span>
                <span><span class="info-label">Date:</span> 
                    {{ $this->order->created_at->format('d M Y H:i') }}
                </span>
            </div>
        </div>

        <table class="items-table">
            <thead><tr><th>Product</th><th>IMEI</th><th>Price</th></tr></thead>
            <tbody>
                @foreach ($this->order->items as $item)
                <tr>
                    <td>{{ $item->unit->product->name }}</td>
                    <td>{{ $item->unit->imei }}</td>
                    <td>₹{{ number_format($item->price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Summary: Subtotal, Discount, Total --}}
        <div>
            <div class="summary-row">
                <span>Subtotal</span>
                <span>₹{{ number_format($this->order->subtotal, 2) }}</span>
            </div>
            @if(($this->order->discount_amount ?? 0) > 0)
            <div class="summary-row">
                <span>Discount ({{ $this->order->discount_percent ?? 0 }}%)</span>
                <span>- ₹{{ number_format($this->order->discount_amount, 2) }}</span>
            </div>
            @endif
            <div class="total-row">
                Total: ₹{{ number_format($this->order->total, 2) }}
            </div>
        </div>

        <p style="font-size: 10px;">
        • 1 month warranty<br>
        • LCD leh mahni tihchhiat a huam lo<br>
        • Memo tello in warranty a claim theihloh<br>
        • Siam theihloh anih loh chuan Exchange tawp a theih ngawt loh
        </p>

        <div class="center">
            <p>Thank You! Visit Again</p>
        </div>

        <div class="text-center no-print">
            <button class="print-button" onclick="window.print()">🖨️ Print Receipt</button>
        </div>
    </div>
</x-filament-panels::page>