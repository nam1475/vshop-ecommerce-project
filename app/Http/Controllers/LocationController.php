<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{
    public function getProvinces()
    {
        $response = Http::get('https://provinces.open-api.vn/api/p/');
        return $response->json();
    }

    public function getDistricts($provinceCode)
    {
        $response = Http::get("https://provinces.open-api.vn/api/p/{$provinceCode}?depth=2");
        return $response->json()['districts'];
    }

    public function getWards($districtCode)
    {
        $response = Http::get("https://provinces.open-api.vn/api/d/{$districtCode}?depth=2");
        return $response->json()['wards'];
    }

    public function getProvinceName($provinceCode)
    {
        $response = Http::get("https://provinces.open-api.vn/api/p/{$provinceCode}");
        return $response->json()['name'];
    }
}

