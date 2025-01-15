<?php

namespace Modules\Category\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;
use Modules\Category\Http\Requests\StoreCategoryRequest;
use Modules\Category\Http\Requests\UpdateCategoryRequest;
use Modules\Category\Services\CategoryService;
use Modules\Category\Transformers\CategoryResource;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryController extends Controller
{

    private array $filters = ['name'];

    public function __construct(Request $request, protected CategoryService $categoryService)
    {
        $this->setConstruct($request, CategoryResource::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        try {
            $includes = explode(',', $request->input('include'));
            $categories = $this->categoryService->all($includes, $this->filters, $this->page, $this->perPage);
            return $this->collection($categories);
        } catch (InternalErrorException $e) {
            Log::error('DataBase Error: ' . $e->getMessage());
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        }

    }

    /**
     * Get all trashed records.
     *
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function getTrashed(): AnonymousResourceCollection|JsonResponse
    {
        try {
            $categories = $this->categoryService->allTrashed($this->filters, $this->page, $this->perPage);
            return $this->collection($categories);
        } catch (InternalErrorException $e) {
            Log::error('DataBase Error: ' . $e->getMessage());
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        }

    }

    /**
     * @param StoreCategoryRequest $request
     * @return JsonResponse|mixed
     */
    public function store(StoreCategoryRequest $request): mixed
    {
        try {
            $category = $this->categoryService->create($request->validated());
            return $this->resource($category);
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
            $category = $this->categoryService->find($id);
            if (!$category) {
                return $this->error(Response::HTTP_NOT_FOUND, 'Resource not found!');
            }
            return $this->resource($category->load('categories', 'models'));

        } catch (InternalErrorException $e) {
            Log::error('DataBase Error: ' . $e->getMessage());
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        }

    }

    /**
     * @param UpdateCategoryRequest $request
     * @param $id
     * @return JsonResponse|mixed
     */
    public function update(UpdateCategoryRequest $request, $id): mixed
    {
        try {
            $updatedCategory = $this->categoryService->update($request->validated(), $id);
            if (!$updatedCategory) {
                return $this->error(Response::HTTP_NOT_FOUND, 'Resourse not found!');
            }
            return $this->resource($updatedCategory);

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
            $deletedCategory = $this->categoryService->delete($id);

            if (!$deletedCategory) {
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
            $restoreCategory = $this->categoryService->restoreCategory($id);
            if (!$restoreCategory) {
                return $this->error(Response::HTTP_NOT_FOUND, 'Resource not found to do the restore operation or is already restored');
            }
            return $this->success([], 'Resource restored successfully!');

        } catch (NotFoundHttpException) {
            return $this->error(Response::HTTP_NOT_FOUND, 'The parent category is deleted, you should restore the parent category to restore the required category!');
        } catch (InternalErrorException $e) {
            Log::error('DataBase Error: ' . $e->getMessage());
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        }
    }
}
