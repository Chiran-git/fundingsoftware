<?php

namespace App\Http\Requests\Campaign;

use App\Rules\CheckAmount;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRewardRequest extends FormRequest
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
            'title' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'min_amount' => ['required', new CheckAmount, 'not_in:0'],
            'quantity' => ['required', 'numeric'],
            'image' => ['sometimes', 'nullable','image','max:2048'],
        ];
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        $messages = [
            'min_amount.not_in' => __('Min amount cannot be 0.'),
        ];

        return $messages;
    }
}
