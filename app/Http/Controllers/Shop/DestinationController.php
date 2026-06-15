<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Services\RajaOngkirService;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function search(Request $request, RajaOngkirService $rajaOngkir)
    {
        $keyword = $request->query('q', '');

        if (strlen($keyword) < 3) {
            return response()->json([]);
        }

        $results = $rajaOngkir->searchDestination($keyword);

        return response()->json($results);
    }
}