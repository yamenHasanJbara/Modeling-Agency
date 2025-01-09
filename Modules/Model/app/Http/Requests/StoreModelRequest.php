<?php

namespace Modules\Model\Http\Requests;

use App\Http\Traits\JsonValidationErrors;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreModelRequest extends FormRequest
{
    use JsonValidationErrors;

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return
        [
            'name' => ['required', 'min:6', 'max:50'],
            'date_of_birth' => ['required', 'date', 'date_format:Y-m-d'],
            'height' => ['required', 'integer'],
            'shoe_size' => ['required', 'integer'],
            'picture' => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
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
