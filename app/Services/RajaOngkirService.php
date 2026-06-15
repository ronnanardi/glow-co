<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RajaOngkirService
{
    

    protected string $apiKey;
    protected string $baseUrl = 'https://rajaongkir.komerce.id/api/v1';

    public function __construct()
    {
        $this->apiKey = env('RAJAONGKIR_KEY');
    }

    // Cari kota/kecamatan
    public function searchDestination(string $keyword)
    {
        $response = Http::withHeaders([
            'key' => $this->apiKey,
        ])->get("{$this->baseUrl}/destination/domestic-destination", [
            'search' => $keyword,
            'limit'  => 20,
        ]);

        return $response->json('data');
    }

    // Cek ongkir
    public function checkOngkir(string $destinationId, int $weight, string $courier)
    {
        $response = Http::withHeaders([
            'key' => $this->apiKey,
        ])->asForm()->post("{$this->baseUrl}/calculate/domestic-cost", [
            'origin'      => env('RAJAONGKIR_ORIGIN'),
            'destination' => $destinationId,
            'weight'      => $weight,
            'courier'     => $courier,
        ]);

        $data = $response->json('data') ?? [];

        // Normalisasi ke format yang dipakai JS
        return collect($data)->map(function ($item) {
            return [
                'service'     => $item['service'],
                'description' => $item['description'],
                'cost'        => [[
                    'value' => $item['cost'],
                    'etd'   => $item['etd'],
                ]],
            ];
        })->values()->toArray();
        }
}