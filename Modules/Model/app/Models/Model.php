<?php

namespace Modules\Model\Models;

use Illuminate\Database\Eloquent\Model as LaravelModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Category\Models\Category;
use Modules\Model\Database\Factories\ModelFactory;

// use Modules\Model\Database\Factories\ModelFactory;

class Model extends LaravelModel
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = ['id'];


    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }


    protected static function newFactory(): ModelFactory
    {
         return ModelFactory::new();
    }
}
