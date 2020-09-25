<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateAutoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->method() === 'PUT') {
            return [
                'name' => 'sometimes|string',
                'number' => 'sometimes|string',
                'color' => 'sometimes|string',
                'vin' => ['sometimes', 'string', Rule::unique('autos', 'vin')->ignore($this->route()->originalParameter('auto'))],
            ];
        }
        return [
            'name' => 'required|string',
            'number' => 'required|string',
            'color' => 'required|string',
            'vin' => 'required|string',
        ];
    }
}
