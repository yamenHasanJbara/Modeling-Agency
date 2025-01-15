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
        protected ModelRepositoryInterface    $modelRepository,
        protected CategoryRepositoryInterface $categoryRepository,
        protected BookingRepositoryInterface  $bookingRepository
    )
    {
    }

    /**
     * @param array $includes
     * @param array $filters
     * @param int $page
     * @param int $perPage
     * @return mixed
     */
    public function all(array $includes, array $filters, int $page, int $perPage): mixed
    {
        return $this->modelRepository->all($includes, $filters, $page, $perPage);
    }


    /**
     * @param array $filters
     * @param int $page
     * @param int $perPage
     * @return mixed
     */
    public function allTrashed(array $filters, int $page, int $perPage): mixed
    {
        return $this->modelRepository->getTrashed($filters, $page, $perPage);
    }


    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data): mixed
    {
        $pictureName = $data['name'] . ' ' . time() . '.' . $data['picture']->getClientOriginalExtension();
        Storage::put('profile_pictures/' . $pictureName, file_get_contents($data['picture']));
        $data['picture'] = $pictureName;
        $model = $this->modelRepository->create($data);

        // if failed to save a new model in the DB, but we already upload the image, so we need to delete the image from the storage.
        if (!$model && Storage::exists('profile_pictures/' . $pictureName)) {
            Storage::delete('profile_pictures/' . $pictureName);
        }

        return $model;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function find(int $id): mixed
    {
        return $this->modelRepository->find($id);
    }

    /**
     * update model, and check if picture is send with the request.
     *
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function update(array $data, $id): mixed
    {
        if (isset($data['picture'])) {
            $pictureName = $data['name'] . ' ' . time() . '.' . $data['picture']->getClientOriginalExtension();
            Storage::put('profile_pictures/' . $pictureName, file_get_contents($data['picture']));
            $data['picture'] = $pictureName;
        }

        $model = $this->modelRepository->update($data, $id);

        if ($model['old_picture'] !== null && Storage::exists('profile_pictures/' . $model['old_picture'])) {
            Storage::delete('profile_pictures/' . $model['old_picture']);
        }

        return $model['model'];
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function delete(int $id): mixed
    {
        $model = $this->modelRepository->delete($id);

        if (!$model) {
            return $model;
        }

        // This is depending on the logic, if we want to delete the picture or not, because we are using soft delete.

        // if ($model['model'] && Storage::exists('profile_pictures/'.$model['picture'])) {
        //     Storage::delete('profile_pictures/'.$model['picture']);
        // }

        return $model['model'];
    }

    /**
     * restore model with all the old bookings.
     *
     * @param int $id
     * @return null
     */
    public function restore(int $id)
    {
        $model = $this->modelRepository->findTrashed($id);
        if (!$model) {
            return null;
        }

        $category = $this->categoryRepository->find($model->category_id);
        if (!$category) {
            throw new NotFoundHttpException('');
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
