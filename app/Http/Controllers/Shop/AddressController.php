<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Services\RajaOngkirService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Address::where('user_id', Auth::id())->latest()->get();
        return view('customer.shop.addresses.index', compact('addresses'));
    }

    public function create(RajaOngkirService $rajaOngkir)
    {
        return view('customer.shop.addresses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'label'          => 'required|string|max:50',
            'recipient_name' => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'address'        => 'required|string',
            'city_id'        => 'required|string',
            'city'           => 'required|string',
            'province'       => 'required|string',
            'postal_code'    => 'required|string|max:10',
            'is_primary'     => 'nullable|boolean',
        ]);

        Address::create([
            'user_id'        => Auth::id(),
            'label'          => $request->label,
            'recipient_name' => $request->recipient_name,
            'phone'          => $request->phone,
            'address'        => $request->address,
            'city_id'        => $request->city_id,
            'city'           => $request->city,
            'province'       => $request->province,
            'postal_code'    => $request->postal_code,
            'is_primary'     => $request->boolean('is_primary'),
        ]);

        return redirect()->route('addresses.index')->with('success', 'Alamat berhasil ditambahkan.');
    }

    public function edit(Address $address)
    {
        abort_if($address->user_id !== Auth::id(), 403);

        return view('customer.shop.addresses.edit', compact('address'));
    }

    public function update(Request $request, Address $address)
    {
        abort_if($address->user_id !== Auth::id(), 403);

        $request->validate([
            'label'          => 'required|string|max:50',
            'recipient_name' => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'address'        => 'required|string',
            'city_id'        => 'required|string',
            'city'           => 'required|string',
            'province'       => 'required|string',
            'postal_code'    => 'required|string|max:10',
            'is_primary'     => 'nullable|boolean',
        ]);

        $address->update([
            'label'          => $request->label,
            'recipient_name' => $request->recipient_name,
            'phone'          => $request->phone,
            'address'        => $request->address,
            'city_id'        => $request->city_id,
            'city'           => $request->city,
            'province'       => $request->province,
            'postal_code'    => $request->postal_code,
            'is_primary'     => $request->boolean('is_primary'),
        ]);

        return redirect()->route('addresses.index')->with('success', 'Alamat berhasil diupdate.');
    }

    public function destroy(Address $address)
    {
        abort_if($address->user_id !== Auth::id(), 403);

        $address->delete();

        return redirect()->route('addresses.index')->with('success', 'Alamat berhasil dihapus.');
    }
}