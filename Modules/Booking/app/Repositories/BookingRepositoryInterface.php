<?php

namespace Modules\Booking\Repositories;

interface BookingRepositoryInterface
{
    public function all(array $includes, array $filters, int $page, int $perPage);

    public function create(array $data);

    public function update(array $data, int $id);

    public function find(int $id);

    public function delete(int $id);

    public function checkIfModelAvailable(int $modelId, $date);

    public function restore(int $id);
}
