<?php

namespace App\Http\Requests\Organization;

use App\Rules\IsOrganizationAdmin;
use Illuminate\Foundation\Http\FormRequest;

class ValidateEmailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'email' => ['required', 'string', 'email', 'max:255', new IsOrganizationAdmin(request()->all(), request()->route('organization'))],
        ];
    }
}
