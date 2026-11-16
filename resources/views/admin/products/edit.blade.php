@extends('layouts.admin')
@section('title', 'Edit Produk')

@section('content')
<form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
    @method('PUT')
    @include('admin.products._form')
</form>
@endsection
