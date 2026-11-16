@extends('layouts.admin')
@section('title', 'Pengaturan')

@section('content')
<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="max-w-xl space-y-4">
    @csrf @method('PUT')

    <div>
        <label class="block text-sm text-slate-300 mb-1">Company Logo</label>
        @if($settings['company_logo'])
            <img src="{{ asset('storage/'.$settings['company_logo']) }}" class="w-16 h-16 object-contain bg-slate-800 rounded mb-2 p-1">
        @endif
        <input type="file" name="company_logo" accept="image/*" class="text-sm text-slate-300">
        @error('company_logo') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    @php
        $fields = [
            'company_name' => 'Nama Perusahaan',
            'company_description' => 'Deskripsi Perusahaan',
            'whatsapp_number' => 'Nomor WhatsApp Admin (format: 62xxxxxxxxxx)',
            'email' => 'Email',
            'payment_information' => 'Informasi Pembayaran (rekening/e-wallet)',
            'invoice_information' => 'Informasi Tambahan di Invoice',
        ];
    @endphp

    @foreach($fields as $key => $label)
        <div>
            <label class="block text-sm text-slate-300 mb-1">{{ $label }}</label>
            @if(in_array($key, ['company_description', 'payment_information', 'invoice_information']))
                <textarea name="{{ $key }}" rows="3"
                          class="w-full rounded-md bg-slate-900 border border-slate-700 px-3 py-2 text-sm text-slate-100">{{ old($key, $settings[$key]) }}</textarea>
            @else
                <input type="text" name="{{ $key }}" value="{{ old($key, $settings[$key]) }}"
                       class="w-full rounded-md bg-slate-900 border border-slate-700 px-3 py-2 text-sm text-slate-100">
            @endif
        </div>
    @endforeach

    <button class="rounded-md bg-sky-500 text-slate-950 font-semibold px-5 py-2 text-sm hover:bg-sky-400">Simpan Pengaturan</button>
</form>
@endsection
