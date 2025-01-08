<?php

namespace Modules\Booking\Http\Requests;

use App\Http\Traits\JsonValidationErrors;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{

    use JsonValidationErrors;

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return
        [
            'customer_name' => ['required', 'string', 'min:6', 'max:100'],
            'booking_date' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:today'],
            'model_id' => ['required', 'integer', Rule::exists('models', 'id')]
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
