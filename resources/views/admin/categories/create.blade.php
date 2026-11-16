@extends('layouts.admin')
@section('title', 'Tambah Kategori')

@section('content')
<form method="POST" action="{{ route('admin.categories.store') }}">
    @include('admin.categories._form')
</form>
@endsection
