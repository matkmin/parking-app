<?php

namespace App\Http\Requests;

use \Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'vehicle_name' => ['required','string', 'max:255'],
            'vehicle_type' => ['required','string', 'max:255'],
            'year' => ['required', 'date_format:Y-m-d'],
            'price'=> ['required','integer'],
            'is_available' => ['required', 'in:0,1'],
        ];
    }
}
