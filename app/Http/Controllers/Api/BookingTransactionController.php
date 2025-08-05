<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingTransactionRequest;
use App\Models\BookingTransaction;
use App\Models\OfficeSpace;
use DateTime;

class BookingTransactionController extends Controller
{
    public function store(StoreBookingTransactionRequest $request)
    {
        $data = $request->validated();
        dd($data);
        $officeSpace = OfficeSpace::findOrFail($data['office_space_id']);
        $data['booking_trx_id'] = 'BK' . date('Ymd') . strtoupper(substr(md5(uniqid()), 0, 6));
        // generated acal booking trx id
        $data['is_paid'] = false;
        $data['duration'] = $officeSpace->duration;
        $startDate = new DateTime($data['started_at']);
        $endDate = $startDate->modify("+{$officeSpace->duration} days");
        $data['ended_at'] = $endDate->format('Y-m-d');
        $booking = BookingTransaction::create($data);
        return response()->json([
            'success' => true,
            'message' => 'Booking created successfully',
            'data' => $booking,
        ], 201);
    }
}
