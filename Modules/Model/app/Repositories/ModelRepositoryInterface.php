<?php

namespace Modules\Model\Repositories;

interface ModelRepositoryInterface
{
    public function all(array $includes, array $filters, int $page, int $perPage);

    public function getTrashed(array $filters, int $page, int $perPage);

    public function create(array $data);

    public function update(array $data, int $id);

    public function find(int $id);

    public function findTrashed(int $id);

    public function delete(int $id);

    public function restore(int $id);
}
