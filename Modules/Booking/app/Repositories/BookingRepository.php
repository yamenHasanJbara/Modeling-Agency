<?php

namespace Modules\Booking\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Booking\Models\Booking;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

class BookingRepository implements BookingRepositoryInterface
{

    /**
     * Get All booking with ability to filter on the customer_name, booking_date.
     *
     * @param array $includes
     * @param array $filters
     * @param int $page
     * @param int $perPage
     * @return LengthAwarePaginator
     * @throws InternalErrorException
     */
    public function all(array $includes, array $filters, int $page, int $perPage)
    {
        try {
            return QueryBuilder::for(Booking::class)
                ->allowedIncludes($includes)
                ->allowedFilters($filters)
                ->defaultSort('-id')
                ->paginate($perPage, ['*'], 'page', $page);
        } catch (\Exception) {
            throw new InternalErrorException('');
        }
    }

    /**
     * Create Booking record.
     *
     * @param array $data
     * @return Model|Booking
     * @throws InternalErrorException
     */
    public function create(array $data): Model|Booking
    {
        try {
            return Booking::query()->create($data);
        } catch (\Exception) {
            throw new InternalErrorException('');
        }

    }

    /**
     * Update Booking record.
     *
     * @param array $data
     * @param int $id
     * @return Collection|Model|Booking|null
     * @throws InternalErrorException
     */
    public function update(array $data, int $id): Model|Collection|Booking|null
    {
        try {
            $booking = Booking::query()->find($id);

            if (!$booking) {
                return null;
            }
            $booking->update($data);

            return $booking;
        } catch (\Exception) {
            throw new InternalErrorException('');
        }
    }

    /**
     * Find Booking based on passed ID.
     *
     * @param int $id
     * @return Collection|Model|Booking|null
     * @throws InternalErrorException
     */
    public function find(int $id): Model|Collection|Booking|null
    {
        try {
            return Booking::query()->find($id);
        } catch (\Exception) {
            throw new InternalErrorException('');
        }
    }

    /**
     * Delete Booking record.
     *
     * @param int $id
     * @return bool|null
     * @throws InternalErrorException
     */
    public function delete(int $id): ?bool
    {
        try {
            $booking = Booking::query()->find($id);
            if (!$booking) {
                return null;
            }

            return $booking->delete();
        } catch (\Exception) {
            throw new InternalErrorException('');
        }
    }

    /**
     * Check the availability for model
     *
     * @param int $modelId
     * @param $date
     * @return bool
     * @throws InternalErrorException
     */
    public function checkIfModelAvailable(int $modelId, $date): bool
    {
        try {
            $booking = Booking::query()
                ->where('model_id', '=', $modelId)
                ->where('booking_date', '=', $date)
                ->whereNull('deleted_at')->first();

            // This means that model is available
            if (!$booking) {
                return true;
            }

            return false;
        } catch (\Exception) {
            throw new InternalErrorException('');
        }
    }

    /**
     * Restore record that deleted using soft delete through the models' module.
     *
     * @param int $id
     * @return void
     */
    public function restore(int $id): void
    {
        $booking = Booking::query()->onlyTrashed()->find($id);
        if ($booking && $booking->trashed()) {
            $booking->restore();
        }
    }
}
