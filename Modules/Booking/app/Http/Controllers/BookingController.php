<?php

namespace Modules\Booking\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;
use Modules\Booking\Http\Requests\StoreBookingRequest;
use Modules\Booking\Http\Requests\UpdateBookingRequest;
use Modules\Booking\Services\BookingService;
use Modules\Booking\Transformers\BookingResource;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\HttpFoundation\Response;

class BookingController extends Controller
{

    private array $filters = ['customer_name', 'booking_date'];

    /**
     * @param Request $request
     * @param BookingService $bookingService
     */
    public function __construct(Request $request, protected BookingService $bookingService)
    {
        $this->setConstruct($request, BookingResource::class);
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        try {
            $includes = explode(',', $request->input('include'));
            $bookings = $this->bookingService->all($includes, $this->filters, $this->page, $this->perPage);
            return $this->collection($bookings);

        } catch (InternalErrorException $e) {
            Log::error('DataBase Error: ' . $e->getMessage());
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        }
    }

    /**
     * @param StoreBookingRequest $request
     * @return JsonResponse|mixed
     */
    public function store(StoreBookingRequest $request): mixed
    {
        try {
            $booking = $this->bookingService->create($request->validated());
            if (!$booking) {
                return $this->error(Response::HTTP_CONFLICT, 'This model is not available for today, please select another date');
            }
            return $this->resource($booking);

        } catch (InternalErrorException $e) {
            Log::error('DataBase Error: ' . $e->getMessage());
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        }
    }

    /**
     * @param $id
     * @return JsonResponse|mixed
     */
    public function show($id): mixed
    {
        try {
            $booking = $this->bookingService->find($id);
            if (!$booking) {
                return $this->error(Response::HTTP_NOT_FOUND, 'Resource not found!');
            }
            return $this->resource($booking->load('model'));

        } catch (InternalErrorException $e) {
            Log::error('DataBase Error: ' . $e->getMessage());
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        }

    }

    /**
     * @param UpdateBookingRequest $request
     * @param $id
     * @return JsonResponse|mixed
     */
    public function update(UpdateBookingRequest $request, $id): mixed
    {
        try {
            $updatedBooking = $this->bookingService->update($request->validated(), $id);
            if (!$updatedBooking) {
                return $this->error(Response::HTTP_NOT_FOUND, 'Resource not found or model is not free at this date!');
            }
            return $this->resource($updatedBooking);

        } catch (InternalErrorException $e) {
            Log::error('DataBase Error: ' . $e->getMessage());
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        }

    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $deletedBooking = $this->bookingService->delete($id);
            if (!$deletedBooking) {
                return $this->error(Response::HTTP_NOT_FOUND, 'Resource not found to do the delete operation or it is already deleted');
            }
            return $this->success([], 'Resource deleted successfully!');

        } catch (InternalErrorException $e) {
            Log::error('DataBase Error: ' . $e->getMessage());
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        }
    }
}
