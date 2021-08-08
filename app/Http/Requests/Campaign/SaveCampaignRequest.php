<?php

namespace App\Http\Requests\Campaign;

use App\Campaign;
use App\Rules\CheckAmount;
use App\Rules\ContainsVideoUrl;
use Illuminate\Foundation\Http\FormRequest;

class SaveCampaignRequest extends FormRequest
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
            'name' => ['sometimes', 'required', 'string'],
            'campaign_category_id' => ['required', 'numeric'],
            'fundraising_goal' => ['sometimes', new CheckAmount, 'not_in:0'],
            'video_url' => ['sometimes', 'nullable', 'string', new ContainsVideoUrl],
            'description' => ['sometimes', 'required', 'string'],
            'image' => ['sometimes', 'nullable', 'image', 'dimensions:min_width=650,min_height=350','max:2048'],
            'donor_message' => ['sometimes', 'required', 'string'],
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
            'fundraising_goal.not_in' => __('Donation amount cannot be 0.'),
            'payout_connected_account_id.required_if' => __('Account is required.'),
            'campaign_category_id.required' => __('Please select a category.')
        ];
    }
}
