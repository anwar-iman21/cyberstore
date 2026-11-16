<div class="px-5 py-5 border-b border-slate-800 flex items-center gap-2">
    @if($logo = \App\Models\Setting::get('company_logo'))
        <img src="{{ asset('storage/'.$logo) }}" class="w-7 h-7 object-contain">
    @endif
    <div>
        <span class="text-lg font-bold text-sky-400">{{ \App\Models\Setting::get('company_name', 'CyberStore') }}</span>
        <div class="text-xs text-slate-500">Admin Panel</div>
    </div>
</div>
<nav class="flex-1 px-3 py-4 space-y-1 text-sm">
    @php
        $links = [
            ['route' => 'admin.dashboard', 'label' => 'Dashboard'],
            ['route' => 'admin.categories.index', 'label' => 'Kategori'],
            ['route' => 'admin.products.index', 'label' => 'Produk'],
            ['route' => 'admin.orders.index', 'label' => 'Pesanan'],
            ['route' => 'admin.customers.index', 'label' => 'Pelanggan'],
            ['route' => 'admin.settings.edit', 'label' => 'Pengaturan'],
        ];
    @endphp
    @foreach($links as $link)
        <a href="{{ route($link['route']) }}"
           class="block rounded-md px-3 py-2 {{ request()->routeIs($link['route'].'*') ? 'bg-sky-500/10 text-sky-400 font-medium' : 'text-slate-300 hover:bg-slate-800' }}">
            {{ $link['label'] }}
        </a>
    @endforeach
</nav>
