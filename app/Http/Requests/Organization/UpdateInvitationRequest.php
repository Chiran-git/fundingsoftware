<?php

namespace App\Http\Requests\Organization;

use App\Rules\ContainsNumber;
use App\Rules\ContainsUppercase;
use App\Rules\ContainsLowercase;
use App\Rules\ContainsSpecialChar;
use Illuminate\Foundation\Http\FormRequest;

class UpdateInvitationRequest extends FormRequest
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
        return [
            'password' => ['sometimes', 'required', 'string', 'confirmed',
                new ContainsLowercase, new ContainsUppercase,
                'min:8', new ContainsNumber, new ContainsSpecialChar],
        ];
    }
}
