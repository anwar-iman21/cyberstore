{{--
    Partial timeline status order (README bagian R & S).
    Wajib pass variable $order dengan relasi statusHistories sudah di-load.
--}}
@php
    $steps = \App\Models\Order::TIMELINE_STEPS;
    $stepKeys = array_keys($steps);
    $currentIndex = array_search($order->status, $stepKeys, true);
    $isTerminalBad = in_array($order->status, [\App\Models\Order::STATUS_REJECTED, \App\Models\Order::STATUS_CANCELLED], true);
@endphp

@if($isTerminalBad)
    @php
        $lastHistory = $order->statusHistories->last();
    @endphp
    <div class="rounded-lg border border-red-800 bg-red-900/20 p-4">
        <div class="flex items-center gap-2 text-red-300 font-semibold">
            <span class="text-lg">&#10005;</span>
            <span>{{ $order->status === \App\Models\Order::STATUS_REJECTED ? 'Pesanan Ditolak' : 'Pesanan Dibatalkan' }}</span>
        </div>
        @if($lastHistory?->note)
            <p class="text-sm text-red-200 mt-2">Alasan: {{ $lastHistory->note }}</p>
        @endif
    </div>
@else
    <ol class="space-y-3">
        @foreach($steps as $key => $label)
            @php
                $stepIndex = array_search($key, $stepKeys, true);
                $state = $stepIndex < $currentIndex ? 'done' : ($stepIndex === $currentIndex ? 'current' : 'upcoming');
            @endphp
            <li class="flex items-center gap-3 text-sm">
                <span class="w-5 h-5 rounded-full flex items-center justify-center shrink-0
                    {{ $state === 'done' ? 'bg-emerald-500 text-slate-950' : ($state === 'current' ? 'bg-sky-500 text-slate-950' : 'bg-slate-800 text-slate-500') }}">
                    {{ $state === 'done' ? '✓' : ($state === 'current' ? '●' : '○') }}
                </span>
                <span class="{{ $state === 'upcoming' ? 'text-slate-500' : 'text-slate-100' }}">{{ $label }}</span>
            </li>
        @endforeach
    </ol>
@endif
