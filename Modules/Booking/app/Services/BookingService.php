<?php

namespace Modules\Booking\Services;

use Modules\Booking\Repositories\BookingRepositoryInterface;

class BookingService
{
    /**
     * Inject the repository.
     */
    public function __construct(
        protected BookingRepositoryInterface $bookingRepository
    )
    {
    }

    /**
     * Get All Available bookings
     */
    public function all(array $includes, array $filters, int $page, int $perPage)
    {
        return $this->bookingRepository->all($includes, $filters, $page, $perPage);
    }

    /**
     * @param array $data
     * @return null
     */
    public function create(array $data)
    {
        $checkIfModelAvailable = $this->bookingRepository->checkIfModelAvailable($data['model_id'], $data['booking_date']);
        if (!$checkIfModelAvailable) {
            return null;
        }

        return $this->bookingRepository->create($data);
    }

    /**
     * Get booking.
     *
     * @param int $id
     * @return mixed
     */
    public function find(int $id): mixed
    {
        return $this->bookingRepository->find($id);
    }

    /**
     * @param array $data
     * @param $id
     * @return null
     */
    public function update(array $data, $id)
    {
        $checkIfModelAvailable = $this->bookingRepository->checkIfModelAvailable($data['model_id'], $data['booking_date']);
        if (!$checkIfModelAvailable) {
            return null;
        }

        return $this->bookingRepository->update($data, $id);
    }

    /**
     * Delete booking.
     *
     * @param int $id
     * @return mixed
     */
    public function delete(int $id): mixed
    {
        return $this->bookingRepository->delete($id);
    }
}
