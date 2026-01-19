<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Http\Requests\BookingRequest;
use Throwable;

class BookingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $bookings = Booking::query();

            // Search
            if ($request->search) {
                $bookings->where(function ($q) use ($request) {
                    $q->where('customer_name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
                });
            }

            // Status filter
            if ($request->status) {
                $bookings->where('status', $request->status);
            }

            // Date range filter
            if ($request->from_date) {
                $bookings->whereDate('booking_date', '>=', $request->from_date);
            }

            if ($request->to_date) {
                $bookings->whereDate('booking_date', '<=', $request->to_date);
            }

            // Sorting (safe)
            $allowedSorts = ['booking_date', 'status', 'customer_name'];

            $sortBy = in_array($request->sort_by, $allowedSorts)
                ? $request->sort_by
                : 'booking_date';

            $sortOrder = $request->sort_order === 'asc' ? 'asc' : 'desc';

            $bookings->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page');

            $paginated = $bookings->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Bookings fetched successfully',
                'data' => $paginated->items(),
                'meta' => [
                    'current_page' => $paginated->currentPage(),
                    'last_page'    => $paginated->lastPage(),
                    'per_page'     => $paginated->perPage(),
                    'total'        => $paginated->total(),
                ],
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch bookings',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function store(BookingRequest $request): JsonResponse
    {
        try {
            $booking = Booking::create($request->validated());

            BookingLog::create([
                'booking_id' => $booking->id,
                'action' => 'created',
                'payload' => $booking->toArray(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully',
                'data' => $booking,
            ], 201);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create booking',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $booking = Booking::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Booking details fetched successfully',
                'data' => $booking,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found',
                'error'   => $e->getMessage(),
            ], 404);
        }
    }

    public function update(BookingRequest $request, int $id): JsonResponse
    {
        try {
            $booking = Booking::findOrFail($id);
            $booking->update($request->validated());

            BookingLog::create([
                'booking_id' => $booking->id,
                'action' => 'updated',
                'payload' => $booking->toArray(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking updated successfully',
                'data' => $booking,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update booking',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $booking = Booking::findOrFail($id);
            $booking->update(['status' => 'cancelled']);

            BookingLog::create([
                'booking_id' => $booking->id,
                'action' => 'cancelled',
                'payload' => $booking->toArray()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking cancelled successfully',
                'data' => null,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel booking',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function getBookingStatus(): JsonResponse
    {
        try {
            $bookings = Booking::select('status')
                ->selectRaw('COUNT(*) as total')
                ->groupBy('status')
                ->get();

            $response = [
                'pendingBooking'   => 0,
                'confirmedBooking' => 0,
                'cancelledBooking' => 0,
                'totalBooking'     => Booking::count(),
            ];

            foreach ($bookings as $booking) {
                if ($booking->status === 'pending') {
                    $response['pendingBooking'] = $booking->total;
                }
                if ($booking->status === 'confirmed') {
                    $response['confirmedBooking'] = $booking->total;
                }
                if ($booking->status === 'cancelled') {
                    $response['cancelledBooking'] = $booking->total;
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Booking status fetched successfully',
                'data' => $response,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch booking status',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
