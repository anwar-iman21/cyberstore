@extends('layouts.admin')
@section('title', 'Tambah Produk')

@section('content')
<form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
    @include('admin.products._form')
</form>
@endsection
