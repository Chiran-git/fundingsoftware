<?php

namespace App\Http\Requests\Admin;

use App\Rules\ContainsNumber;
use App\Rules\ContainsUppercase;
use App\Rules\ContainsLowercase;
use App\Rules\ContainsSpecialChar;
use Illuminate\Foundation\Http\FormRequest;

class CreateAdminRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed',
                new ContainsLowercase, new ContainsUppercase,
                'min:8', new ContainsNumber, new ContainsSpecialChar],
            'password_confirmation' => ['required', 'string',
                new ContainsLowercase, new ContainsUppercase,
                'min:8', new ContainsNumber, new ContainsSpecialChar],
            'image' => ['sometimes', 'nullable', 'image', 'dimensions:min_width=100,min_height=100', 'max:1048'],
        ];
    }
}
