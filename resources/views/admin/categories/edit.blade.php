@extends('layouts.admin')
@section('title', 'Edit Kategori')

@section('content')
<form method="POST" action="{{ route('admin.categories.update', $category) }}">
    @method('PUT')
    @include('admin.categories._form')
</form>
@endsection
