<?php

namespace Modules\Model\Services;

use Illuminate\Support\Facades\Storage;
use Modules\Model\Repositories\ModelRepositoryInterface;

class ModelService{

    /**
     * Inject the repository.
     */
    public function __construct(
        protected ModelRepositoryInterface $modelRepository
    )
    {}


    public function all(array $includes, array $filters, int $page, int $perPage)
    {
        return $this->modelRepository->all($includes, $filters, $page, $perPage);
    }


    public function create(array $data)
    {
        $pictureName = $data['name'] . ' ' . time() . '.' . $data['picture']->getClientOriginalExtension();
        Storage::put('profile_pictures/' . $pictureName, file_get_contents($data['picture']));
        $data['picture'] = $pictureName;
        $model =  $this->modelRepository->create($data);

        // if failed to save a new model in the DB, but we already upload the image, so we need to delete the image from the storage.
        if(!$model && Storage::exists('profile_pictures/' . $pictureName)){
            Storage::delete('profile_pictures/' . $pictureName);
        }

        return $model;
    }


    public function find(int $id)
    {
        return $this->modelRepository->find($id);
    }


    public function update(array $data, $id)
    {
        if (isset($data['picture'])) {
            $pictureName = $data['name'] . ' ' . time() . '.' . $data['picture']->getClientOriginalExtension();
            Storage::put('profile_pictures/' . $pictureName, file_get_contents($data['picture']));
            $data['picture'] = $pictureName;
        }

        $model =  $this->modelRepository->update($data, $id);
        if($model['old_picture'] !== null && Storage::exists('profile_pictures/'. $model['old_picture'])){
            Storage::delete('profile_pictures/'. $model['old_picture']);
        }

        return $model['model'];
    }


    public function delete(int $id)
    {
        $model =  $this->modelRepository->delete($id);

        if (!$model) {
            return $model;
        }

        if($model['model'] && Storage::exists('profile_pictures/'. $model['picture'])){
            dd('reach here');
            Storage::delete('profile_pictures/'. $model['picture']);
        }

        return $model['model'];
    }

}
