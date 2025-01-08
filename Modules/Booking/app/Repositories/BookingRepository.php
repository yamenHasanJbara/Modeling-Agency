<?php

namespace Modules\Booking\Repositories;

use Modules\Booking\Models\Booking;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

class BookingRepository implements BookingRepositoryInterface{


    /**
     * Get All booking with abilty to filter on the customer_name, booking_date.
     */
    public function all(array $includes, array $filters, int $page, int $PerPage){
        return QueryBuilder::for(Booking::class)
        ->allowedIncludes($includes)
        ->allowedFilters($filters)
        ->defaultSort('-id')
        ->paginate($PerPage, ['*'], 'page', $page);
    }


    /**
     * Create Booking record.
     */
    public function create(array $data)
    {
        try {
            return Booking::query()->create($data);
        } catch (\Throwable $th) {
            return new InternalErrorException('');
        }

    }

    /**
     * Update Booking record.
     */
    public function update(array $data, int $id)
    {
        try {
            $booking = Booking::query()->find($id);

            if (!$booking) {
                return null;
            }
            $booking->update($data);

           return $booking;
        } catch (\Throwable $th) {
            return new InternalErrorException('');
        }
    }

    /**
     * Find Booking based on passed ID.
     */
    public function find(int $id)
    {
        try {
            return Booking::query()->find($id);
        } catch (\Throwable $th) {
            return new InternalErrorException('');
        }
    }

    /**
     * Delete Booking record.
     */
    public function delete(int $id)
    {
        try {
            $booking =  Booking::query()->find($id);
            if (!$booking) {
                return null;
            }

           return $booking->delete();
        } catch (\Throwable $th) {
            return new InternalErrorException('');
        }
    }

    /**
     * Check the availability for model
     */
    public function checkIfModeAvailable(int $modelId, $date)
    {
        try {

            $booking = Booking::query()
            ->where('model_id', '=', $modelId)
            ->where('booking_date', '=', $date)->first();

            if (!$booking) {
                return true;
            }

            return false;
        } catch (\Throwable $th) {
            return new InternalErrorException('');
        }
    }

}
