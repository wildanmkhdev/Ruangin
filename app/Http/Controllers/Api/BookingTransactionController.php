<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingTransactionRequest;
use App\Http\Resources\Api\ViewBookingResource;
use App\Models\BookingTransaction;
use App\Models\OfficeSpace;
use DateTime;
use Illuminate\Http\Request;

class BookingTransactionController extends Controller
{
    public function booking_details(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'booking_trx_id' => 'required|string',
        ]);

        $booking = BookingTransaction::where('phone_number', $request->phone_number)
            ->where('booking_trx_id', $request->booking_trx_id)
            ->with(['officeSpace', 'officeSpace.city'])
            ->first();

        if (!$booking) {
            return response()->json([
                'message' => 'Booking is not found'
            ], 404);
        }

        // ✅ Return booking data ketika ditemukan
        return response()->json([
            'success' => true,
            'message' => 'Booking found successfully',
            'data' => new ViewBookingResource($booking)
        ], 200);
    }
    public function store(StoreBookingTransactionRequest $request)
    {
        $data = $request->validated();
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
