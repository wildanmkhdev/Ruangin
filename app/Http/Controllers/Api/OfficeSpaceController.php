<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\OfficeSpaceResource;
use App\Models\OfficeSpace;
use Illuminate\Http\Request;

class OfficeSpaceController extends Controller
{

    public function index()
    {
        $officeSpaces = OfficeSpace::with('city')->get();
        // ambil seluruhh data officespace dnegan kotanya
        return OfficeSpaceResource::collection($officeSpaces);
        // pakai colection karena kita mau tampilkan lebih dari satu data city
    }
//tampilkan detail kanotr yg di pilih kota photo dan benefits
    public function show(OfficeSpace $officeSpace) //model binding di laravel
    {
        $officeSpace->load(['city', 'photos', 'benefits']);

        return new OfficeSpaceResource($officeSpace);
    }
}
