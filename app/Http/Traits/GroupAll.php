<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;

trait GroupAll
{
    use ApiResponser, JsonResponse, Pagination;

    protected $perPage;

    protected $page;

    public function setConstruct(Request $request, $resource)
    {
        $this->setResource($resource);
        $this->perPage = $this->checkPerPageValue($request);
        $this->page = $this->checkPageValue($request);
    }
}
