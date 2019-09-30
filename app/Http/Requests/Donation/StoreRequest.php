<?php

namespace App\Http\Requests\Donation;

use App\DonorQuestion;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Make sure this is an active campaign
        $campaign = request()->route('campSlug');

        if (! $campaign->isActive()) {
            return false;
        }

        // If reward id is in the request, then it must belong to the campaign
        if (request()->reward) {
            return in_array(
                request()->reward,
                $campaign->rewards()->get()->pluck('id')->toArray()
            );
        }

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
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'card_name' => ['required', 'string'],
            'amount' => [Rule::requiredIf(! request()->reward), 'numeric'],
            'reward' => ['nullable', 'numeric'],
            'stripe_token' => ['required', 'string'],
        ];

        // Add donor question rules
        $rules = array_merge($rules, $this->questionRules());

        // Add system donor question rules
        $rules = array_merge($rules, $this->systemQuestionRules());

        return $rules;
    }

    /**
     * Method to return dynamic donor question validation rules
     *
     * @return array
     */
    private function questionRules()
    {
        $return = [];

        $questions = (array)request()->questions;
        $campaign = request()->route('campSlug');

        foreach ($questions as $questionId) {
            $return['question_' . $questionId] = function ($attribute, $value, $fail) use ($questionId, $campaign) {
                $question = DonorQuestion::where('id', $questionId)
                    ->where('organization_id', $campaign->organization_id)
                    ->first();
                // If question not found
                if (! $question) {
                    $fail(__('This field does not exist.'));
                    return;
                }

                // If question is required, then we should have a value
                if ($question->is_required && ! trim($value)) {
                    $fail(__('This is a required field.'));
                }
            };
        }

        return $return;
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
        $campaign = request()->route('campSlug');
        $organization = $campaign->organization;

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
