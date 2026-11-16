<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = User::where('role', User::ROLE_CUSTOMER)
            ->withCount('orders')
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%'.$request->string('q').'%';
                $q->where(fn ($qq) => $qq->where('name', 'like', $term)->orWhere('email', 'like', $term));
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }
}
