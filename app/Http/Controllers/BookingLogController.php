<?php

namespace App\Http\Controllers;

use App\Models\BookingLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Throwable;

class BookingLogController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $bookingLogs = BookingLog::get();

            return response()->json([
                'success' => true,
                'message' => 'Bookings fetched successfully',
                'data' => $bookingLogs,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch bookings',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
