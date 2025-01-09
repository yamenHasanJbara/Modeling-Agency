<?php

namespace Modules\Category\Repositories;

use Modules\Category\Models\Category;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * Get All Category with abilty to filter on name of the category.
     */
    public function all(array $includes, array $filters, int $page, int $PerPage)
    {
        try {
            return QueryBuilder::for(Category::class)
            ->allowedIncludes($includes)
            ->allowedFilters($filters)
            ->defaultSort('-id')
            ->paginate($PerPage, ['*'], 'page', $page);
        } catch (\Throwable $th) {
            return new InternalErrorException('');
        }

    }

    /**
     * Get soft deleted records
     */
    public function getTrashed(array $filters, int $page, int $PerPage)
    {
        try {
            return QueryBuilder::for(Category::class)
            ->onlyTrashed()
            ->allowedFilters($filters)
            ->defaultSort('-id')
            ->paginate($PerPage, ['*'], 'page', $page);
        } catch (\Throwable $th) {
            return new InternalErrorException('');
        }
    }

    /**
     * Create Category record.
     */
    public function create(array $data)
    {
        try {
            return Category::query()->create([
                'name' => $data['name'],
                'category_id' => $data['category_id'] ?? null,
            ]);
        } catch (\Throwable $th) {
            return new InternalErrorException('');
        }

    }

    /**
     * Update Category record.
     */
    public function update(array $data, int $id)
    {
        try {
            $category = Category::query()->find($id);

            if (! $category) {
                return null;
            }
            $category->update($data);

            return $category;
        } catch (\Throwable $th) {
            return new InternalErrorException('');
        }
    }

    /**
     * Find Category based on passed ID.
     */
    public function find(int $id)
    {
        try {
            return Category::query()->find($id);
        } catch (\Throwable $th) {
            return new InternalErrorException('');
        }
    }

    /**
     * Delete Category record.
     */
    public function delete(int $id)
    {
        try {
            $category = Category::query()->find($id);
            if (! $category) {
                return null;
            }

            return $category->delete();
        } catch (\Throwable $th) {
            return new InternalErrorException('');
        }
    }

    /**
     * Restore category, we need to check if the parent is not deleted, if not deleted, restore is okay
     * other wise, if the parent is deleted, shoul restore the parent, then restore the target category
     */
    public function restore(int $id)
    {
        try {
            $category = QueryBuilder::for(Category::class)
                ->onlyTrashed()
                ->find($id);

            if ($category && $category->trashed() && $category->category_id === null) {
                $category->restore();

                return true;
            }

            if ($category && $category->trashed() && $category->category_id !== null) {
                $checkCategoryParent = Category::query()->find($category->category_id);
                if (! $checkCategoryParent) {
                    return new NotFoundHttpException('');
                }

                $category->restore();

                return true;
            }

            return null;
        } catch (\Throwable $th) {
            return new InternalErrorException('');
        }
    }
}
