<?php

namespace Modules\Category\Services;

use Modules\Category\Repositories\CategoryRepositoryInterface;

class CategoryService{

    /**
     * Inject the repository.
     */
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository
    )
    {}


    public function all(array $includes, array $filters, int $page, int $perPage)
    {
        return $this->categoryRepository->all($includes, $filters, $page, $perPage);
    }


    public function create(array $data)
    {
        return $this->categoryRepository->create($data);
    }


    public function find(int $id)
    {
        return $this->categoryRepository->find($id);
    }


    public function update(array $data, $id)
    {
        return $this->categoryRepository->update($data, $id);
    }


    public function delete(int $id)
    {
        return $this->categoryRepository->delete($id);
    }

}
