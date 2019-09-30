<?php

namespace App\Http\Requests\Organization;

use Illuminate\Validation\Rule;
use App\Rules\IsNotReservedString;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()->isSuperAdmin() || $this->user()->isAppAdmin()) {
            return true;
        }
        return $this->user()->can('update', $this->route('organization'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'address1' => ['required', 'string', 'max:255'],
            'address2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:4'],
            'zipcode' => ['required', 'string', 'min:5', 'max:12'],
            'phone' => ['required', 'string', 'max:22'],
            'currency' => ['required', 'alpha', 'size:3'],
            //'country' => ['sometimes', 'alpha', 'size:2'],
            'slug' => ['required', 'string', new IsNotReservedString,
                'alpha_dash', 'max:255', Rule::unique('organizations')->ignore($this->route('organization'))],
        ];
    }
}
