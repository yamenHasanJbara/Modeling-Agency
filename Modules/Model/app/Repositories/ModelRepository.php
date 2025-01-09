<?php

namespace Modules\Model\Repositories;

use Modules\Model\Models\Model;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

class ModelRepository implements ModelRepositoryInterface
{
    /**
     * Get All Model with abilty to filter on the name, height.
     */
    public function all(array $includes, array $filters, int $page, int $PerPage)
    {
        try {
            return QueryBuilder::for(Model::class)
            ->allowedIncludes($includes)
            ->allowedFilters($filters)
            ->defaultSort('-id')
            ->paginate($PerPage, ['*'], 'page', $page);
        } catch (\Throwable $th) {
            return new InternalErrorException('');
        }
    }

    /**
     * Get all trashed models
     */
    public function getTrashed(array $filters, int $page, int $PerPage)
    {
       try {
        return QueryBuilder::for(Model::class)
        ->onlyTrashed()
        ->allowedFilters($filters)
        ->defaultSort('-id')
        ->paginate($PerPage, ['*'], 'page', $page);
       } catch (\Throwable $th) {
        return new InternalErrorException('');
       }
    }

    /**
     * Create Model record.
     */
    public function create(array $data)
    {
        try {
            return Model::query()->create($data);
        } catch (\Throwable $th) {
            return new InternalErrorException('');
        }

    }

    /**
     * Update Model record.
     */
    public function update(array $data, int $id)
    {
        try {
            $model = Model::query()->find($id);
            if (! $model) {
                return null;
            }
            $oldPicture = isset($data['picture']) ? $model->picture : null;
            $model->update($data);

            return [
                'model' => $model,
                'old_picture' => $oldPicture,
            ];
        } catch (\Throwable $th) {
            return new InternalErrorException('');
        }
    }

    /**
     * Find Model based on passed ID.
     */
    public function find(int $id)
    {
        try {
            return Model::query()->find($id);
        } catch (\Throwable $th) {
            return new InternalErrorException('');
        }
    }

    public function findTrashed(int $id)
    {
        try {
            return Model::query()->onlyTrashed()->find($id);
        } catch (\Throwable $th) {
            return new InternalErrorException('');
        }
    }

    /**
     * Delete Model record.
     */
    public function delete(int $id)
    {
        try {
            $model = Model::query()->find($id);
            if (! $model) {
                return null;
            }

            return
            [
                'picture' => $model->picture,
                'model' => $model->delete(),
            ];
        } catch (\Throwable $th) {
            return new InternalErrorException('');
        }
    }

    /**
     * restore model
     */
    public function restore(int $id)
    {
        try {
            $model = Model::query()->onlyTrashed()->find($id);
            if ($model->trashed()) {
                $model->restore();

                return true;
            }
        } catch (\Throwable $th) {
            return new InternalErrorException('');
        }
    }
}
