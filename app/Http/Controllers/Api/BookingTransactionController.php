<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingTransactionRequest;
use App\Http\Resources\Api\ViewBookingResource;
use App\Models\BookingTransaction;
use App\Models\OfficeSpace;
use DateTime;
use Illuminate\Http\Request;
use Twilio\Rest\Client;

class BookingTransactionController extends Controller

{
    public function index()
    {
        $bookings = BookingTransaction::with(['officeSpace', 'officeSpace.city'])->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'All bookings fetched successfully',
            'data' => ViewBookingResource::collection($bookings)
        ]);
    }

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
    // data ini akan kita kiriim ke fe yg akan di tangkap oleh fe memalu stategit
    public function store(StoreBookingTransactionRequest $request)
    {
        $data = $request->validated();
        $officeSpace = OfficeSpace::findOrFail($data['office_space_id']);
        $data['booking_trx_id'] = 'BK' . date('Ymd') . strtoupper(substr(md5(uniqid()), 0, 6));
        // generated acal booking trx id
        $data['is_paid'] = false;
        $data['duration'] = $officeSpace->duration;
        $startDate = new DateTime($data['started_at']);
        // ambil data tanggal terbaru dengan format baru
        $endDate = $startDate->modify("+{$officeSpace->duration} days");
        // setlah data started_at di ambil kemudian cari office duraton lalu tambahkan
        // jadi misalnya user masukkan tangagl 10 jika office duration kita 10hari jadi masa berakhirnya tanggal 20
        $data['ended_at'] = $endDate->format('Y-m-d');
        // simpan ke dalam endedt_at dengan format ymd
        $sid = getenv("TWILIO_ACCOUNT_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio = new Client($sid, $token);
        $booking = BookingTransaction::create($data);
        $messageBody = "hi {$booking->name},terima kasih telah bokking kantor di ruangin .\n\n";
        $messageBody .= "pesanan kantor {$booking->officeSpace->name},anda sedang kami proses dengan bokking trx id: {$booking->booking_trx_id} .\n\n";
        $messageBody .= "kami akan menginformasikan kembali pemesaanan and secepat mungkin";
        $message = $twilio->messages->create(
            // "+15558675310", 
            "+{$booking->phone_number}", // To
            [
                "body" => $messageBody,
                "from" => getenv("TWILIO_PHONE_NUMBER"),
            ]
        );

        // $booking->load('officeSpace');
        return response()->json([
            'success' => true,
            'message' => 'Booking created successfully',
            'data' => $booking,
        ], 201);
    }
}
