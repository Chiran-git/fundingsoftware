<?php

namespace App\Http\Requests\Donor;

use App\Rules\CheckAmount;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class CreateDonationRequest extends FormRequest
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
        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'donation_method' => ['required', 'string', 'max:255'],
            'gross_amount' => ['required', new CheckAmount, 'not_in:0'],
            'check_number' => ['sometimes', 'nullable', 'required_if:donation_method,check', 'string'],
            'campaign_id' => ['required'],
        ];

        // Add system donor question rules
        $rules = array_merge($rules, $this->systemQuestionRules());

        foreach ($this->request->get('donor_answers') as $key => $val) {
            if ($val['is_required']) {
                $rules['donor_answers.'.$key.'.answer'] = 'required';
            }
        }

        return $rules;
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        $messages = [
            'payout_method.required' => __('Donation method is required.'),
            'check_number.required_if' => __('Check number is required.'),
            'gross_amount.required' => __('Donation amount is required.'),
            'gross_amount.numeric' => __('Donation amount must be a number.'),
            'gross_amount.not_in' => __('Donation amount cannot be 0'),
            'campaign_id.required' => __('Campaign is required.'),
        ];

        foreach ($this->request->get('donor_answers') as $key => $val) {
            if ($val['is_required']) {
                $messages['donor_answers.'.$key.'.answer.required'] = __('Answer is required.');
            }
        }

        return $messages;
    }

    /**
     * Method to return dynamic system donor question validation rules
     *
     * @return array
     */
    private function systemQuestionRules()
    {
        $return = [];
        $mailingAddressFields = [
            'mailing_address1',
            'mailing_city',
            'mailing_state',
            'mailing_zipcode'
        ];
        $organization = request()->user()->currentOrganization();

        if (isset($organization->system_donor_questions) && ! empty($organization->system_donor_questions)) {
            $organization->system_donor_questions = json_decode($organization->system_donor_questions);
        }
        if (isset($organization->system_donor_questions->mailing_address)
            && isset($organization->system_donor_questions->mailing_address->enabled)
            && $organization->system_donor_questions->mailing_address->enabled
            && $organization->system_donor_questions->mailing_address->required) {

            foreach ($mailingAddressFields as $field) {
                $return[$field] = 'required';
            }
        }

        if (isset($organization->system_donor_questions->comment)
            && isset($organization->system_donor_questions->comment->enabled)
            && $organization->system_donor_questions->comment->enabled
            && $organization->system_donor_questions->comment->required) {
            $return['comments'] = 'required';
        }

        return $return;
    }
}
