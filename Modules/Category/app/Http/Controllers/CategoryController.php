<?php

namespace Modules\Category\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Category\Http\Requests\StoreCategoryRequest;
use Modules\Category\Http\Requests\UpdateCategoryRequest;
use Modules\Category\Services\CategoryService;
use Modules\Category\Transformers\CategoryResource;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryController extends Controller
{
    private $includes;

    private $filters = ['name'];

    public function __construct(Request $request, protected CategoryService $categoryService)
    {
        return $this->setConstruct($request, CategoryResource::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->includes = explode(',', $request->input('include'));
        $categories = $this->categoryService->all($this->includes, $this->filters, $this->page, $this->perPage);
        if ($categories instanceof InternalErrorException) {
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        }
        return $this->collection($categories);
    }

    /**
     * Get all trashed rcords
     */
    public function getTrashed()
    {
        $categories = $this->categoryService->allTrashed($this->filters, $this->page, $this->perPage);
        if ($categories instanceof InternalErrorException) {
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        }
        return $this->collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = $this->categoryService->create($request->validated());
        if ($category instanceof InternalErrorException) {
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        }

        return $this->resource($category);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $category = $this->categoryService->find($id);
        if (! $category) {
            return $this->error(Response::HTTP_NOT_FOUND, 'Resource not found!');
        }

        if ($category instanceof InternalErrorException) {
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        }

        return $this->resource($category->load('categories', 'models'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $updatedCategory = $this->categoryService->update($request->validated(), $id);
        if (! $updatedCategory) {
            return $this->error(Response::HTTP_NOT_FOUND, 'Resourse not found!');
        }

        if ($updatedCategory instanceof InternalErrorException) {
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        }

        return $this->resource($updatedCategory);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $deletedCategory = $this->categoryService->delete($id);

        if (! $deletedCategory) {
            return $this->error(Response::HTTP_NOT_FOUND, 'Resource not found to do the delete operation or is already deleted');
        }

        if ($deletedCategory instanceof InternalErrorException) {
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        }

        return $this->success([], 'Resource deleted successfully!');
    }

    /**
     * Resotre category
     */
    public function restore(int $id)
    {
        $restoreCategory = $this->categoryService->restoreCategory($id);

        if (! $restoreCategory) {
            return $this->error(Response::HTTP_NOT_FOUND, 'Resource not found to do the restore operation or is already restored');
        }

        if ($restoreCategory instanceof NotFoundHttpException) {
            return $this->error(Response::HTTP_NOT_FOUND, 'The parent category is deleted, you should restore the parent category to restore the required category!');
        }

        if ($restoreCategory instanceof InternalErrorException) {
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong, please try again later!');
        }

        return $this->success([], 'Resource restored successfully!');
    }
}
