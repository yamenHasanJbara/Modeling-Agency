<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;

trait Pagination
{
    public $modelPerPage = 20;

    public $modelPage = 1;

    public function checkPerPageValue(Request $request)
    {
        return $request->perPage ?? $this->modelPerPage;
    }

    public function checkPageValue(Request $request)
    {
        return $request->page ?? $this->modelPage;
    }
}
