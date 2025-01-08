<?php


namespace App\Repositories;



interface CrudRepositoryInterface {

    public function all(array $includes, array $filters, int $page, int $perPage);
    public function create(array $data);
    public function update(array $data, int $id);
    public function find(int $id);
    public function delete(int $id);
}