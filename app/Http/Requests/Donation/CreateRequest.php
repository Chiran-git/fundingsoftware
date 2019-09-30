<?php

namespace App\Http\Requests\Donation;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // If reward id is in the request, then it must belong to the campaign
        if (request()->reward) {
            $campaign = request()->route('campSlug');
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
        return [
            'amount' => 'required|numeric',
            'reward' => 'sometimes|nullable|numeric',
        ];
    }
}
