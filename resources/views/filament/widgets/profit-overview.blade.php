
<x-filament::widget>
    <x-filament::card class="relative overflow-hidden">
        {{-- Subtle background accent --}}
        <div class="absolute top-0 right-0 w-32 h-32 bg-green-100 dark:bg-green-900/20 rounded-full -mr-16 -mt-16 opacity-50"></div>

        <div class="relative z-10">
            {{-- Header with icon and title --}}
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    {{-- <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div> --}}
                    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Profit Calculation</h2>
                </div>
            </div>

            {{-- Date range form – inline and clean --}}
            <div class="mb-5">
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-1">
                        {{ $this->form }}
                    </div>
                </div>
            </div>

            {{-- Profit value with large font and proper styling --}}
            <div class="mt-2 text-center sm:text-left">
                <div class="text-4xl md:text-5xl font-extrabold text-green-600 dark:text-green-400">
                    <b>₹{{ number_format($profit, 2) }}</b>
                </div>
                <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Total profit from 
                    <span class="font-medium text-gray-700 dark:text-gray-300">
                        {{ \Carbon\Carbon::parse($start)->format('d M Y') }}
                    </span> 
                    to 
                    <span class="font-medium text-gray-700 dark:text-gray-300">
                        {{ \Carbon\Carbon::parse($end)->format('d M Y') }}
                    </span>
                </div>
            </div>

            {{-- Optional extra note --}}
            <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-800 text-xs text-gray-400">
                Based on order totals minus product costs (cost_price recorded at time of sale).
            </div>
        </div>
    </x-filament::card>
</x-filament::widget>