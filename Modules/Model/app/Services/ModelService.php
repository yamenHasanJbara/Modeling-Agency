<?php

namespace Modules\Model\Services;

use Illuminate\Support\Facades\Storage;
use Modules\Booking\Repositories\BookingRepositoryInterface;
use Modules\Category\Repositories\CategoryRepositoryInterface;
use Modules\Model\Repositories\ModelRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ModelService
{
    /**
     * Inject the repository.
     */
    public function __construct(
        protected ModelRepositoryInterface $modelRepository,
        protected CategoryRepositoryInterface $categoryRepository,
        protected BookingRepositoryInterface $bookingRepository
    ) {}

    /**
     * Get all models
     */
    public function all(array $includes, array $filters, int $page, int $perPage)
    {
        return $this->modelRepository->all($includes, $filters, $page, $perPage);
    }

    /**
     * Get all trashed models
     */
    public function allTrashed(array $filters, int $page, int $perPage)
    {
        return $this->modelRepository->getTrashed($filters, $page, $perPage);
    }

    /**
     * create model, with saving picture
     */
    public function create(array $data)
    {
        $pictureName = $data['name'].' '.time().'.'.$data['picture']->getClientOriginalExtension();
        Storage::put('profile_pictures/'.$pictureName, file_get_contents($data['picture']));
        $data['picture'] = $pictureName;
        $model = $this->modelRepository->create($data);

        // if failed to save a new model in the DB, but we already upload the image, so we need to delete the image from the storage.
        if (! $model && Storage::exists('profile_pictures/'.$pictureName)) {
            Storage::delete('profile_pictures/'.$pictureName);
        }

        return $model;
    }

    /**
     * Get one model
     */
    public function find(int $id)
    {
        return $this->modelRepository->find($id);
    }

    /**
     * update model, and check if picture is send with the request
     */
    public function update(array $data, $id)
    {
        if (isset($data['picture'])) {
            $pictureName = $data['name'].' '.time().'.'.$data['picture']->getClientOriginalExtension();
            Storage::put('profile_pictures/'.$pictureName, file_get_contents($data['picture']));
            $data['picture'] = $pictureName;
        }

        $model = $this->modelRepository->update($data, $id);

        if ($model['old_picture'] !== null && Storage::exists('profile_pictures/'.$model['old_picture'])) {
            Storage::delete('profile_pictures/'.$model['old_picture']);
        }

        return $model['model'];
    }

    /**
     * delete model
     */
    public function delete(int $id)
    {
        $model = $this->modelRepository->delete($id);

        if (! $model) {
            return $model;
        }

        // This is depending on the logic, if we want to delete the picture or not.

        // if ($model['model'] && Storage::exists('profile_pictures/'.$model['picture'])) {
        //     Storage::delete('profile_pictures/'.$model['picture']);
        // }

        return $model['model'];
    }

    /**
     * restore model with all the old bookings
     */
    public function restore(int $id)
    {
        $model = $this->modelRepository->findTrashed($id);
        if (! $model) {
            return null;
        }

        $category = $this->categoryRepository->find($model->category_id);
        if (! $category) {
            return new NotFoundHttpException('');
        }

        $modelRestoreResult = $this->modelRepository->restore($id);
        if ($modelRestoreResult) {
            $modelRestored = $this->modelRepository->find($id);
            $bookings = $modelRestored->trashedBookings()->pluck('id');

            foreach ($bookings as $booking) {
                $this->bookingRepository->restore($booking);
            }
        }

        return $modelRestoreResult;
    }
}
