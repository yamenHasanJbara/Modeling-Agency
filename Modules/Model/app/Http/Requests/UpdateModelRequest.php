<?php

namespace Modules\Model\Http\Requests;

use App\Http\Traits\JsonValidationErrors;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateModelRequest extends FormRequest
{
    use JsonValidationErrors;

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return
        [
            'name' => ['min:6', 'max:50'],
            'date_of_birth' => ['date', 'date_format:Y-m-d'],
            'height' => ['integer'],
            'shoe_size' => ['integer'],
            'picture' => ['image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'category_id' => ['integer', Rule::exists('categories', 'id')],
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
