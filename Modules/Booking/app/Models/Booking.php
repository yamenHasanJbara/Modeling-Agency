<?php

namespace Modules\Booking\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as LaravelModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Booking\Database\Factories\BookingFactory;
use Modules\Model\Models\Model as ModelsModel;

// use Modules\Booking\Database\Factories\BookingFactory;

class Booking extends LaravelModel
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = ['id'];

    public function model(): BelongsTo
    {
        return $this->belongsTo(ModelsModel::class);
    }

    protected static function newFactory(): BookingFactory
    {
        return BookingFactory::new();
    }
}
