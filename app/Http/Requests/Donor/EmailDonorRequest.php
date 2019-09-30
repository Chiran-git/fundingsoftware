<?php

namespace App\Http\Requests\Donor;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class EmailDonorRequest extends FormRequest
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
            'subject' => ['required', 'string'],
            'message' => ['required', 'string'],
        ];

        return $rules;
    }
}
