<?php

namespace App\Http\Requests\Campaign;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCampaignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //authorize for update request
        if ($this->route('campaign')) {
            return $this->user()->can('update', [$this->route('campaign'), $this->route('organization')]);
        }

        return $this->user()->can('create', Campaign::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'payout_method' => ['sometimes', 'nullable', 'string', 'in:check,bank'],
            'payout_name' => ['required_if:payout_method,check', 'string'],
            'payout_organization_name' => ['required_if:payout_method,check', 'string'],
            'payout_address1' => ['required_if:payout_method,check', 'nullable', 'string'],
            'payout_city' => ['required_if:payout_method,check', 'string'],
            'payout_state' => ['required_if:payout_method,check', 'string'],
            'payout_zipcode' => ['required_if:payout_method,check', 'string', 'min:5', 'max:12'],
            //'payout_country' => ['required_if:payout_method,check'],
            'payout_payable_to' => ['required_if:payout_method,check', 'string'],
            'payout_schedule' => ['sometimes', 'required', 'string',
                function ($attribute, $value, $fail) {
                    if (request()->get('payout_method') === 'bank' && $value !== 'daily' ) {
                        $fail('Select valid payment schedule.');
                    }
                },
            ],
            'payout_connected_account_id' => ['required_if:payout_method,bank', 'nullable'],
        ];
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
