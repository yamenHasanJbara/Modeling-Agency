<?php

namespace Modules\Model\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Model\Http\Requests\StoreModelRequest;
use Modules\Model\Http\Requests\UpdateModelRequest;
use Modules\Model\Services\ModelService;
use Modules\Model\Transformers\ModelResource;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ModelController extends Controller
{
    private $includes;

    private $filters = ['name', 'height'];

    public function __construct(Request $request, protected ModelService $modelService)
    {
        return $this->setConstruct($request, ModelResource::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->includes = explode(',', $request->input('include'));
        $models = $this->modelService->all($this->includes, $this->filters, $this->page, $this->perPage);
        if ($models instanceof InternalErrorException) {
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        }
        return $this->collection($models);
    }

    public function getTrashed()
    {
        $models = $this->modelService->allTrashed($this->filters, $this->page, $this->perPage);
        if ($models instanceof InternalErrorException) {
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        }
        return $this->collection($models);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreModelRequest $request)
    {
        $model = $this->modelService->create($request->validated());
        if ($model instanceof InternalErrorException) {
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        }

        return $this->resource($model);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $model = $this->modelService->find($id);
        if (! $model) {
            return $this->error(Response::HTTP_NOT_FOUND, 'Resource not found!');
        }

        if ($model instanceof InternalErrorException) {
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        }

        return $this->resource($model->load('category', 'bookings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateModelRequest $request, $id)
    {
        $updatedModel = $this->modelService->update($request->validated(), $id);
        if (! $updatedModel) {
            return $this->error(Response::HTTP_NOT_FOUND, 'Resourse not found!');
        }

        if ($updatedModel instanceof InternalErrorException) {
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        }

        return $this->resource($updatedModel);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $deletedModel = $this->modelService->delete($id);

        if (! $deletedModel) {
            return $this->error(Response::HTTP_NOT_FOUND, 'Resource not found to do the delete operation or is already deleted');
        }

        if ($deletedModel instanceof InternalErrorException) {
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        }

        return $this->success([], 'Resource deleted successfully!');
    }

    /**
     * Resotre model
     */
    public function restore(int $id)
    {
        $model = $this->modelService->restore($id);
        if (! $model) {
            return $this->error(Response::HTTP_NOT_FOUND, 'Resourse not found to do the restore or is already restored!');
        }

        if ($model instanceof NotFoundHttpException) {
            return $this->error(Response::HTTP_NOT_FOUND, 'The category for this model is deleted, you need to restore the category so you can restore the model');
        }

        if ($model instanceof InternalErrorException) {
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        }

        return $this->success([], 'Resource restored successfully!');
    }
}
