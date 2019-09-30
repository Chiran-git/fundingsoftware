<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConnectedAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $organization = $this->route('organization');
        $connectedAccount = $this->route('organizationConnectedAccount');

        return $this->user()->can('update', $organization)
            && $this->user()->can('update', [$connectedAccount, $organization]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nickname' => ['required'],
            'is_default' => ['required', 'boolean']
        ];
    }
}
