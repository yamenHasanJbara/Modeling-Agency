<?php

namespace Modules\Category\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Category\Database\Factories\CategoryFactory;
use Modules\Model\Models\Model as ModelsModel;

// use Modules\Category\Database\Factories\CategoryFactory;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = ['id'];

    protected static function newFactory(): CategoryFactory
    {
        return CategoryFactory::new();
    }

    public function categories(): HasMany
    {
        return $this->hasMany(self::class);
    }

    public function models(): HasMany
    {
        return $this->hasMany(ModelsModel::class);
    }

    protected static function booted()
    {
        static::deleted(function ($category) {
            $category->categories()->delete();
            $category->models()->delete();
        });
    }
}
