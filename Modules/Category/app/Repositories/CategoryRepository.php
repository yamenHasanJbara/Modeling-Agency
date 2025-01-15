<?php

namespace Modules\Category\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Category\Models\Category;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryRepository implements CategoryRepositoryInterface
{

    /**
     * Get All Category with ability to filter on name of the category.
     *
     * @param array $includes
     * @param array $filters
     * @param int $page
     * @param int $perPage
     * @return LengthAwarePaginator
     * @throws InternalErrorException
     */
    public function all(array $includes, array $filters, int $page, int $perPage): LengthAwarePaginator
    {
        try {
            return QueryBuilder::for(Category::class)
                ->allowedIncludes($includes)
                ->allowedFilters($filters)
                ->defaultSort('-id')
                ->paginate($perPage, ['*'], 'page', $page);
        } catch (\Exception) {
            throw new InternalErrorException('');
        }

    }

    /**
     * Get soft deleted records.
     *
     * @param array $filters
     * @param int $page
     * @param int $perPage
     * @return mixed
     * @throws InternalErrorException
     */
    public function getTrashed(array $filters, int $page, int $perPage): mixed
    {
        try {
            return QueryBuilder::for(Category::class)
                ->onlyTrashed()
                ->allowedFilters($filters)
                ->defaultSort('-id')
                ->paginate($perPage, ['*'], 'page', $page);
        } catch (\Exception) {
            throw new InternalErrorException('');
        }

    }

    /**
     * Create Category record.
     *
     * @param array $data
     * @return Model|Category
     * @throws InternalErrorException
     */
    public function create(array $data): Model|Category
    {
        try {
            return Category::query()->create([
                'name' => $data['name'],
                'category_id' => $data['category_id'] ?? null,
            ]);
        } catch (\Exception) {
            throw new InternalErrorException('');
        }


    }

    /**
     * Update Category record.
     *
     * @param array $data
     * @param int $id
     * @return Collection|Model|Category|null
     * @throws InternalErrorException
     */
    public function update(array $data, int $id): Model|Collection|Category|null
    {
        try {
            $category = Category::query()->find($id);

            if (!$category) {
                return null;
            }
            $category->update($data);

            return $category;
        } catch (\Exception) {
            throw new InternalErrorException('');
        }

    }

    /**
     * Find Category based on passed ID.
     *
     * @param int $id
     * @return Collection|Model|Category|null
     * @throws InternalErrorException
     */
    public function find(int $id): Model|Collection|Category|null
    {
        try {
            return Category::query()->find($id);
        } catch (\Exception) {
            throw new InternalErrorException('');
        }

    }

    /**
     * Delete Category record.
     *
     * @param int $id
     * @return bool|null
     * @throws InternalErrorException
     */
    public function delete(int $id): ?bool
    {
        try {
            $category = Category::query()->find($id);
            if (!$category) {
                return null;
            }

            return $category->delete();
        } catch (\Exception) {
            throw new InternalErrorException('');
        }
    }

    /**
     *  Restore category, we need to check if the parent is not deleted, if not deleted, restore is okay
     *  otherwise, if the parent is deleted, should restore the parent, then restore the target category.
     * @param int $id
     * @return true|null
     * @throws InternalErrorException
     */
    public function restore(int $id): ?bool
    {
        try {
            $category = QueryBuilder::for(Category::class)
                ->onlyTrashed()
                ->find($id);

            if (!$category) {
                return null;
            }

            if ($category->category_id === null && $category->trashed()) {
                $category->restore();
                return true;
            }

            if ($category->category_id !== null && $category->trashed()) {
                $checkCategoryParent = Category::query()->find($category->category_id);
                if (!$checkCategoryParent) {
                    throw new NotFoundHttpException('');
                }
                $category->restore();
                return true;
            }
            return false;
        } catch (NotFoundHttpException) {
            throw new NotFoundHttpException('');
        } catch (\Exception) {
            throw new InternalErrorException('');
        }
    }
}
