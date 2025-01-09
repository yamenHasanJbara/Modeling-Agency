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
     * Get All records
     */
    public function all(array $includes, array $filters, int $page, int $perPage)
    {
        return $this->categoryRepository->all($includes, $filters, $page, $perPage);
    }

    /**
     * Get all trashed records
     */
    public function allTrashed(array $filters, int $page, int $perPage)
    {
        return $this->categoryRepository->getTrashed($filters, $page, $perPage);
    }

    /**
     * create category
     */
    public function create(array $data)
    {
        return $this->categoryRepository->create($data);
    }

    /**
     * Get category
     */
    public function find(int $id)
    {
        return $this->categoryRepository->find($id);
    }

    /**
     * Update category
     */
    public function update(array $data, $id)
    {
        return $this->categoryRepository->update($data, $id);
    }

    /**
     * Delete category
     */
    public function delete(int $id)
    {
        return $this->categoryRepository->delete($id);
    }

    /**
     * Restore category
     */
    public function restoreCategory(int $id)
    {
        return $this->categoryRepository->restore($id);
    }
}
