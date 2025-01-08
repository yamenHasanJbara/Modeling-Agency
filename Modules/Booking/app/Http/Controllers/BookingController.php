<?php

namespace Modules\Booking\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Booking\Http\Requests\StoreBookingRequest;
use Modules\Booking\Http\Requests\UpdateBookingRequest;
use Modules\Booking\Services\BookingService;
use Modules\Booking\Transformers\BookingResource;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\HttpFoundation\Response;

class BookingController extends Controller
{
    private $includes;
    private $filters = ['customer_name', 'booking_date'];

    public function __construct(Request $request, protected BookingService $bookingService)
    {
        return $this->setConstruct($request, BookingResource::class);
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->includes = explode(',', $request->input('include'));
        $bookings = $this->bookingService->all($this->includes, $this->filters, $this->page, $this->perPage);
        return $this->collection($bookings);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingRequest $request)
    {
        $booking = $this->bookingService->create($request->validated());

        if (!$booking) {
            return $this->error(Response::HTTP_CONFLICT, 'This model is not available for today, please select another date');
        }

        if ($booking instanceof InternalErrorException) {
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        }

        return $this->resource($booking);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $booking = $this->bookingService->find($id);
        if (!$booking) {
            return $this->error(Response::HTTP_NOT_FOUND, 'Resource not found!');
        }

        if ($booking instanceof InternalErrorException) {
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        }

        return $this->resource($booking->load('model'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookingRequest $request, $id)
    {
        $updatedBooking = $this->bookingService->update($request->validated(), $id);
        if (!$updatedBooking) {
            return $this->error(Response::HTTP_NOT_FOUND, 'Resourse not found or model is not free at this date!');
        }

        if ($updatedBooking instanceof InternalErrorException) {
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        }

        return $this->resource($updatedBooking);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $deletedBooking = $this->bookingService->delete($id);

        if (!$deletedBooking) {
            return $this->error(Response::HTTP_NOT_FOUND, 'Resource not found to do the delete operation or is already deleted');
        }

        if ($deletedBooking instanceof InternalErrorException) {
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        }
        return $this->success([], 'Resource deleted successfully!');
    }
}
