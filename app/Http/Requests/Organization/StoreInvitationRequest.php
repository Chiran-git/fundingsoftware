<?php

namespace App\Http\Requests\Organization;

use App\Rules\IsValidateCampaign;
use App\Rules\IsOrganizationAdmin;
use Illuminate\Foundation\Http\FormRequest;

class StoreInvitationRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', new IsOrganizationAdmin(request()->all(), request()->route('organization'))],
            'role' => ['required', 'string', 'max:255', 'in:owner,admin,campaign-admin'],
            'campaign_ids' => ['sometimes', new IsValidateCampaign(request()->all(), request()->route('organization'))]
        ];
    }
}
