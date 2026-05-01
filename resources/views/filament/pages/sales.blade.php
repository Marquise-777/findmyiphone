<x-filament-panels::page>
    {{-- Single root wrapper --}}
    <div style="max-width: 2200px; margin: 1 auto;">

        {{-- IMEI row --}}
        <div style="display: flex; gap: 2rem; margin-bottom: 2rem; flex-wrap: wrap;">
            <div style="flex: 1;" x-data="{ focusInput() { $refs.imeiInput.focus() } }"
                 x-init="focusInput(); $wire.on('focus-imei', () => focusInput())">
                <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">Scan / Enter IMEI, Serial</label>
                <input x-ref="imeiInput"
                       type="text"
                       wire:model.defer="imei_input"
                       wire:keydown.enter="scanImei"
                       placeholder="Scan IMEI/Serial and Press Enter"
                       style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 1rem;">
                <p style="font-size: 0.75rem; color: #6b7280; margin-top: 0.25rem;">Press Enter after scanning</p>
            </div>
            <div style="flex: 1;">
                 {{ $this->form }}
            </div>
        </div>

        {{-- Two columns: Cart (left) and Summary (right) --}}
        <div style="display: flex; gap: 2rem; flex-wrap: wrap;">

            {{-- LEFT: Cart items --}}
            <div style="flex: 3; min-width: 300px;">
                <div style="border-bottom: 1px solid #e5e7eb; padding-bottom: 0.5rem; margin-bottom: 1rem;">
                    <strong style="font-size: 1.125rem;">Shopping Cart</strong>
                    <span style="font-size: 0.875rem; color: #6b7280; margin-left: 0.5rem;">({{ count($this->cart) }} item(s))</span>
                </div>
                @if(count($this->cart))
                    @foreach ($this->cart as $index => $item)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid #f3f4f6;">
                            <div>
                                <span style="font-weight: 500;">{{ $item['product_name'] }}</span>
                                <span style="font-size: 0.75rem; color: #6b7280; margin-left: 0.5rem;">IMEI</span>
                            </div>
                            <div>
                                <span style="font-family: monospace;">₱{{ number_format($item['price'], 2) }}</span>
                                <button wire:click="removeFromCart({{ $index }})" wire:confirm="Remove item?" style="margin-left: 1rem; color: #ef4444; border: none; background: none; cursor: pointer;">✕</button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div style="text-align: center; padding: 2rem; color: #6b7280;">Cart is empty. Scan an IMEI.</div>
                @endif
            </div>

            {{-- RIGHT: Order Summary with inline discount & payment --}}
            <div style="flex: 1; min-width: 250px;">
                <div style="padding: 1rem; border-radius: 0.5rem; border: 1px solid #e5e7eb;">
                    <strong style="display: block; border-bottom: 1px solid #e5e7eb; padding-bottom: 0.5rem; margin-bottom: 1rem;">Order Summary</strong>

                    @php
                        $subtotal = collect($this->cart)->sum('price');
                        $discountPercent = (float) ($this->discountPercent ?? 0);
                        $discountAmount = $subtotal * ($discountPercent / 100);
                        $tax = 0;
                        $total = $subtotal - $discountAmount + $tax;
                        $paid = (float) $this->paidAmount;
                        $due = max(0, $total - $paid);
                    @endphp

                    <div style="margin-bottom: 1rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span>Subtotal</span>
                            <span style="font-family: monospace;">₱{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <span>Discount</span>
                                <input type="number"
                                    wire:model.live="discountPercent"
                                    step="0.1"
                                    min="0"
                                    max="100"
                                    style="width: 65px; padding: 0.25rem; border: 1px solid #d1d5db; border-radius: 0.25rem; font-size: 0.875rem; text-align: center;">
                                <span>%</span>
                            </div>
                            <span style="font-family: monospace; color: #16a34a;">-₱{{ number_format($discountAmount, 2) }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span>Tax</span>
                            <span style="font-family: monospace;">₱{{ number_format($tax, 2) }}</span>
                        </div>

                        {{-- Payment section --}}
                        <div style="border-top: 1px solid #d1d5db; margin-top: 0.5rem; padding-top: 0.5rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <span>Payment Method</span>
                                <select wire:model="paymentMethod"
                                        style="border: 1px solid #d1d5db; border-radius: 0.25rem; padding: 0.25rem 0.5rem;">
                                    <option value="cash">Cash</option>
                                    <option value="card">Card</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="gcash">GCash</option>
                                </select>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <span>Amount Paid</span>
                                <input type="number" wire:model.live="paidAmount" step="any" min="0"
                                       style="width: 100px; padding: 0.25rem; text-align: right; border: 1px solid #d1d5db; border-radius: 0.25rem;">
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>Due</span>
                                <span style="font-family: monospace; color: #dc2626;">₱{{ number_format($due, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <div style="border-top: 1px solid #d1d5db; padding-top: 0.5rem; display: flex; justify-content: space-between; font-weight: bold;">
                        <span>Total</span>
                        <span style="font-family: monospace;">₱{{ number_format($total, 2) }}</span>
                    </div>

                    <button wire:click="checkout"
                            style="width: 100%; background-color: #10b981; color: white; padding: 0.5rem; border: none; border-radius: 0.375rem; cursor: pointer; font-weight: 500; margin-top: 1rem;">
                        Continue to Checkout →
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>