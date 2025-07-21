<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CityResource;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index()
    {
        // Ambil semua data kota dari tabel City,
        // lalu hitung juga berapa banyak officeSpace (ruang kantor) yang dimiliki tiap kota
        $cities = City::withCount('officeSpaces')->get();

        // Tampilkan semua data kota dalam bentuk JSON yang rapi,
        // karena data-nya lebih dari satu, maka pakai collection
        return CityResource::collection($cities);
    }

    public function show(City $city)
    {
        // Ambil data tambahan (relasi) dari kota ini, yaitu:
        // - daftar ruang kantor (officeSpace)
        // - lalu ambil juga data kota dan foto dari masing-masing ruang kantor
        $city->load(['officeSpaces.city', 'officeSpaces.photos']);

        // Hitung berapa banyak ruang kantor yang dimiliki oleh kota ini
        $city->loadCount('officeSpaces');

        // Tampilkan 1 data kota dalam bentuk JSON yang rapi,
        // karena hanya satu data, maka pakai 'new' bukan 'collection'
        return new CityResource($city);
    }
}
