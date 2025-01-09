<?php

namespace Modules\Booking\Services;

use Modules\Booking\Repositories\BookingRepositoryInterface;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

class BookingService
{
    /**
     * Inject the repository.
     */
    public function __construct(
        protected BookingRepositoryInterface $bookingRepository
    ) {}

    /**
     * Get All Available bookings
     */
    public function all(array $includes, array $filters, int $page, int $perPage)
    {
        return $this->bookingRepository->all($includes, $filters, $page, $perPage);
    }

    /**
     * Create booking
     */
    public function create(array $data)
    {
        $checkIfModelAvailable = $this->bookingRepository->checkIfModelAvailable($data['model_id'], $data['booking_date']);
        if (! $checkIfModelAvailable) {
            return null;
        }

        if ($checkIfModelAvailable instanceof InternalErrorException) {
            return new InternalErrorException('');
        }

        return $this->bookingRepository->create($data);
    }

    /**
     * Get booking
     */
    public function find(int $id)
    {
        return $this->bookingRepository->find($id);
    }

    /**
     * Update booking
     */
    public function update(array $data, $id)
    {
        $checkIfModelAvailable = $this->bookingRepository->checkIfModelAvailable($data['model_id'], $data['booking_date']);
        if (! $checkIfModelAvailable) {
            return null;
        }

        if ($checkIfModelAvailable instanceof InternalErrorException) {
            return new InternalErrorException('');
        }

        return $this->bookingRepository->update($data, $id);
    }

    /**
     * Delete booking
     */
    public function delete(int $id)
    {
        return $this->bookingRepository->delete($id);
    }
}
