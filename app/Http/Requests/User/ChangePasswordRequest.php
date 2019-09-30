<?php

namespace App\Http\Requests\User;

use App\Rules\ContainsNumber;
use App\Rules\ContainsUppercase;
use App\Rules\ContainsLowercase;
use App\Rules\ContainsSpecialChar;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
        $user = auth()->user();
        return [
            'old_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                                                if (!\Hash::check($value, $user->password)) {
                                                    return $fail(__('The old password is incorrect.'));
                                                }
                                            }],
            'password' => ['required', 'string', 'confirmed',
                new ContainsLowercase, new ContainsUppercase,
                'min:8', new ContainsNumber, new ContainsSpecialChar],
            'password_confirmation' => ['required', 'string',
                new ContainsLowercase, new ContainsUppercase,
                'min:8', new ContainsNumber, new ContainsSpecialChar]
        ];
    }
}
