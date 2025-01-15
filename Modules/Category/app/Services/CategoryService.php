<?php

namespace Modules\Category\Services;

use Modules\Category\Repositories\CategoryRepositoryInterface;

class CategoryService
{

    /**
     * Inject the repository.
     */
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository
    ) {}

    /**
     * @param array $includes
     * @param array $filters
     * @param int $page
     * @param int $perPage
     * @return mixed
     */
    public function all(array $includes, array $filters, int $page, int $perPage): mixed
    {
        return $this->categoryRepository->all($includes, $filters, $page, $perPage);
    }

    /**
     * @param array $filters
     * @param int $page
     * @param int $perPage
     * @return mixed
     */
    public function allTrashed(array $filters, int $page, int $perPage): mixed
    {
        return $this->categoryRepository->getTrashed($filters, $page, $perPage);
    }


    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data): mixed
    {
        return $this->categoryRepository->create($data);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function find(int $id): mixed
    {
        return $this->categoryRepository->find($id);
    }


    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function update(array $data, $id): mixed
    {
        return $this->categoryRepository->update($data, $id);
    }


    /**
     * @param int $id
     * @return mixed
     */
    public function delete(int $id): mixed
    {
        return $this->categoryRepository->delete($id);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function restoreCategory(int $id): mixed
    {
        return $this->categoryRepository->restore($id);
    }
}
