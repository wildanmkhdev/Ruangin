<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingTransactionRequest;
use App\Models\BookingTransaction;
use App\Models\OfficeSpace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class BookingTransactionController extends Controller
{
    public function store(StoreBookingTransactionRequest $request)
    {
        try {
            // Validate incoming data
            $validatedData = $request->validated();
            
            // Log untuk debugging (opsional, bisa dihapus nanti)
            Log::info('Booking Transaction Request:', $validatedData);
            
            // Find office space
            $officeSpace = OfficeSpace::find($validatedData['office_space_id']);
            
            if (!$officeSpace) {
                return response()->json([
                    'success' => false,
                    'message' => 'Office space not found'
                ], 404);
            }
            
            // Generate unique transaction ID
            $validatedData['booking_trx_id'] = $this->generateUniqueTrxId();
            
            // Set default values
            $validatedData['is_paid'] = false;
            $validatedData['duration'] = $officeSpace->duration;
            
            // Calculate end date
            $startDate = new \DateTime($validatedData['started_at']);
            $endDate = $startDate->modify("+{$officeSpace->duration} days");
            $validatedData['ended_at'] = $endDate->format('Y-m-d');
            
            // Create booking transaction
            $bookingTransaction = BookingTransaction::create($validatedData);
            
            // Load relationships untuk response yang lengkap
            $bookingTransaction->load('officeSpace');
            
            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully',
                'data' => $bookingTransaction,
            ], 201);
            
        } catch (Exception $e) {
            // Log error untuk debugging
            Log::error('Booking Transaction Error: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Internal server error occurred',
                'error' => config('app.debug') ? $e->getMessage() : 'Something went wrong'
            ], 500);
        }
    }
    
    /**
     * Generate unique transaction ID
     */
    private function generateUniqueTrxId(): string
    {
        do {
            $trxId = 'BK' . date('Ymd') . strtoupper(substr(md5(uniqid()), 0, 6));
        } while (BookingTransaction::where('booking_trx_id', $trxId)->exists());
        
        return $trxId;
    }
}