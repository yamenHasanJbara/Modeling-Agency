<?php

namespace Modules\Category\Http\Requests;

use App\Http\Traits\JsonValidationErrors;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    use JsonValidationErrors;

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return
        [
            'name' => ['required', 'max:100', Rule::unique('categories', 'name')],
            'category_id' => ['integer', Rule::exists('categories', 'id')],
        ];
    }

    public function messages()
    {
        return
        [
            'name.unique' => 'The name has already been taken, check the deleted record or existing once'
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
