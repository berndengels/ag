<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreJobcenterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('write Jobcenter');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'          => 'required|unique:jobcenters,name',
            'street'        => 'required',
            'postcode'      => 'required',
            'city'          => 'required',
            'email'         => 'required|email|unique:jobcenters,email',
            'fon'           => '',
            'opening_time'  => '',
            'locations'     => '',
        ];
    }
}
