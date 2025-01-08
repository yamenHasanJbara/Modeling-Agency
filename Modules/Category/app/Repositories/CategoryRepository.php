<?php

namespace Modules\Category\Repositories;

use App\Repositories\CrudRepositoryInterface;
use Modules\Category\Models\Category;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

class CategoryRepository implements CrudRepositoryInterface{


    /**
     * Get All Category with abilty to filter on name of the category.
     */
    public function all(array $includes, array $filters, int $page, int $PerPage){
        return QueryBuilder::for(Category::class)
        ->allowedIncludes($includes)
        ->allowedFilters($filters)
        ->defaultSort('-id')
        ->paginate($PerPage, ['*'], 'page', $page);
    }


    /**
     * Create Category record.
     */
    public function create(array $data)
    {
        try {
            return Category::query()->create([
                'name' => $data['name'],
                'category_id' => $data['category_id'] ?? null
            ]);
        } catch (\Throwable $th) {
            return null;
        }

    }

    /**
     * Update Category record.
     */
    public function update(array $data, int $id)
    {
        try {
            $category = Category::query()->find($id);

            if (!$category) {
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
            $category =  Category::query()->find($id);
            if (!$category) {
                return null;
            }

            return $category->delete();
        } catch (\Throwable $th) {
            return new InternalErrorException('');
        }
    }
}
