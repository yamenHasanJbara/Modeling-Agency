<?php

namespace Modules\Model\Repositories;

use Spatie\QueryBuilder\QueryBuilder;
use Modules\Model\Models\Model;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

class ModelRepository implements ModelRepositoryInterface{


    /**
     * Get All Model with abilty to filter on the name, height.
     */
    public function all(array $includes, array $filters, int $page, int $PerPage){
        return QueryBuilder::for(Model::class)
        ->allowedIncludes($includes)
        ->allowedFilters($filters)
        ->defaultSort('-id')
        ->paginate($PerPage, ['*'], 'page', $page);
    }


    /**
     * Create Model record.
     */
    public function create(array $data)
    {
        try {
            return Model::query()->create($data);
        } catch (\Throwable $th) {
            return null;
        }

    }

    /**
     * Update Model record.
     */
    public function update(array $data, int $id)
    {
        try {
            $model = Model::query()->find($id);
            if (!$model) {
                return null;
            }
            $oldPicture = isset($data['picture']) ? $model->picture : null;
            $model->update($data);
            return [
                'model' => $model,
                'old_picture' => $oldPicture
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

    /**
     * Delete Model record.
     */
    public function delete(int $id)
    {
        try {
            $model =  Model::query()->find($id);
            if (!$model) {
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
}
