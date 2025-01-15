<?php

namespace Modules\Model\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;
use Modules\Model\Http\Requests\StoreModelRequest;
use Modules\Model\Http\Requests\UpdateModelRequest;
use Modules\Model\Services\ModelService;
use Modules\Model\Transformers\ModelResource;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ModelController extends Controller
{

    private array $filters = ['name', 'height'];

    public function __construct(Request $request, protected ModelService $modelService)
    {
        $this->setConstruct($request, ModelResource::class);
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        try {
            $includes = explode(',', $request->input('include'));
            $models = $this->modelService->all($includes, $this->filters, $this->page, $this->perPage);
            return $this->collection($models);
        } catch (InternalErrorException $e) {
            Log::error('DataBase Error: ' . $e->getMessage());
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        }

    }

    /**
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function getTrashed(): AnonymousResourceCollection|JsonResponse
    {
        try {
            $models = $this->modelService->allTrashed($this->filters, $this->page, $this->perPage);
            return $this->collection($models);
        } catch (InternalErrorException $e) {
            Log::error('DataBase Error: ' . $e->getMessage());
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        }
    }

    /**
     * @param StoreModelRequest $request
     * @return JsonResponse|mixed
     */
    public function store(StoreModelRequest $request): mixed
    {
        try {
            $model = $this->modelService->create($request->validated());
            return $this->resource($model);
        } catch (InternalErrorException $e) {
            Log::error('DataBase Error: ' . $e->getMessage());
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        }
    }


    /**
     * @param $id
     * @return JsonResponse|mixed
     */
    public function show($id): mixed
    {
        try {
            $model = $this->modelService->find($id);
            if (!$model) {
                return $this->error(Response::HTTP_NOT_FOUND, 'Resource not found!');
            }
            return $this->resource($model->load('category', 'bookings'));

        } catch (InternalErrorException $e) {
            Log::error('DataBase Error: ' . $e->getMessage());
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        }
    }


    /**
     * @param UpdateModelRequest $request
     * @param $id
     * @return JsonResponse|mixed
     */
    public function update(UpdateModelRequest $request, $id): mixed
    {
        try {
            $updatedModel = $this->modelService->update($request->validated(), $id);
            if (!$updatedModel) {
                return $this->error(Response::HTTP_NOT_FOUND, 'Resource not found!');
            }
            return $this->resource($updatedModel);

        } catch (InternalErrorException $e) {
            Log::error('DataBase Error: ' . $e->getMessage());
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        }
    }


    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $deletedModel = $this->modelService->delete($id);

            if (!$deletedModel) {
                return $this->error(Response::HTTP_NOT_FOUND, 'Resource not found to do the delete operation or is already deleted');
            }
            return $this->success([], 'Resource deleted successfully!');

        } catch (InternalErrorException $e) {
            Log::error('DataBase Error: ' . $e->getMessage());
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        }
    }


    /**
     * @param int $id
     * @return JsonResponse
     */
    public function restore(int $id): JsonResponse
    {
        try {
            $model = $this->modelService->restore($id);
            if (!$model) {
                return $this->error(Response::HTTP_NOT_FOUND, 'Resourse not found to do the restore or is already restored!');
            }
            return $this->success([], 'Resource restored successfully!');

        } catch (NotFoundHttpException) {
            return $this->error(Response::HTTP_NOT_FOUND, 'The category for this model is deleted, you need to restore the category so you can restore the model');
        } catch (InternalErrorException $e) {
            Log::error('DataBase Error: ' . $e->getMessage());
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        }
    }
}
