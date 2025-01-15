<?php

namespace Modules\Model\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Model\Models\Model;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

class ModelRepository implements ModelRepositoryInterface
{

    /**
     * Get All Model with ability to filter on the name, height.
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
            return QueryBuilder::for(Model::class)
                ->allowedIncludes($includes)
                ->allowedFilters($filters)
                ->defaultSort('-id')
                ->paginate($perPage, ['*'], 'page', $page);
        } catch (\Exception) {
            throw new InternalErrorException('');
        }
    }

    /**
     * Get all trashed models.
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
            return QueryBuilder::for(Model::class)
                ->onlyTrashed()
                ->allowedFilters($filters)
                ->defaultSort('-id')
                ->paginate($perPage, ['*'], 'page', $page);
        } catch (\Exception) {
            throw new InternalErrorException('');
        }
    }

    /**
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model|Model
     * @throws InternalErrorException
     */
    public function create(array $data): \Illuminate\Database\Eloquent\Model|Model
    {
        try {
            return Model::query()->create($data);
        } catch (\Exception) {
            throw new InternalErrorException('');
        }

    }

    /**
     * @param array $data
     * @param int $id
     * @return array
     * @throws InternalErrorException
     */
    public function update(array $data, int $id): array
    {
        try {
            $model = Model::query()->find($id);
            if (!$model) {
                return [
                    'model' => $model,
                    'old_picture' => null,
                ];
            }
            $oldPicture = isset($data['picture']) ? $model->picture : null;
            $model->update($data);

            return [
                'model' => $model,
                'old_picture' => $oldPicture,
            ];
        } catch (\Exception) {
            throw new InternalErrorException('');
        }
    }

    /**
     * @param int $id
     * @return Collection|\Illuminate\Database\Eloquent\Model|Model|null
     * @throws InternalErrorException
     */
    public function find(int $id): \Illuminate\Database\Eloquent\Model|Collection|Model|null
    {
        try {
            return Model::query()->find($id);
        } catch (\Exception) {
            throw new InternalErrorException('');
        }
    }

    /**
     * @param int $id
     * @return mixed
     * @throws InternalErrorException
     */
    public function findTrashed(int $id): mixed
    {
        try {
            return Model::query()->onlyTrashed()->find($id);
        } catch (\Exception) {
            throw new InternalErrorException('');
        }
    }

    /**
     * @param int $id
     * @return array
     * @throws InternalErrorException
     */
    public function delete(int $id): array
    {
        try {
            $model = Model::query()->find($id);
            if (!$model) {
                return
                    [
                        'picture' => null,
                        'model' => $model,
                    ];
            }

            return
                [
                    'picture' => $model->picture,
                    'model' => $model->delete(),
                ];
        } catch (\Exception) {
            throw new InternalErrorException('');
        }
    }

    /**
     * @param int $id
     * @return true|void
     * @throws InternalErrorException
     */
    public function restore(int $id)
    {
        try {
            $model = Model::query()->onlyTrashed()->find($id);
            if ($model->trashed()) {
                $model->restore();

                return true;
            }
        } catch (\Exception) {
            throw new InternalErrorException('');
        }
    }
}
