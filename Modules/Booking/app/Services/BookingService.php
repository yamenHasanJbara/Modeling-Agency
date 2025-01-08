<?php

namespace Modules\Booking\Services;

use Modules\Booking\Repositories\BookingRepositoryInterface;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

class BookingService{

    /**
     * Inject the repository.
     */
    public function __construct(
        protected BookingRepositoryInterface $bookingRepository
    )
    {
    }


    public function all(array $includes, array $filters, int $page, int $perPage)
    {
        return $this->bookingRepository->all($includes, $filters, $page, $perPage);
    }


    public function create(array $data)
    {
        $checkIfModelAvailable = $this->bookingRepository->checkIfModeAvailable($data['model_id'], $data['booking_date']);
        if (!$checkIfModelAvailable) {
            return null;
        }

        if ($checkIfModelAvailable instanceof InternalErrorException) {
            return new InternalErrorException('');
        }

        return $this->bookingRepository->create($data);
    }


    public function find(int $id)
    {
        return $this->bookingRepository->find($id);
    }


    public function update(array $data, $id)
    {
        $checkIfModelAvailable = $this->bookingRepository->checkIfModeAvailable($data['model_id'], $data['booking_date']);
        if (!$checkIfModelAvailable) {
            return null;
        }

        if ($checkIfModelAvailable instanceof InternalErrorException) {
            return new InternalErrorException('');
        }

        return $this->bookingRepository->update($data, $id);
    }


    public function delete(int $id)
    {
        return $this->bookingRepository->delete($id);
    }

}
