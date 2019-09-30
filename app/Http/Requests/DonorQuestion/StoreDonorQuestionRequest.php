<?php

namespace App\Http\Requests\DonorQuestion;

use Illuminate\Foundation\Http\FormRequest;

class StoreDonorQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()->isSuperAdmin() || $this->user()->isAppAdmin()) {
            return true;
        }
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
            'question' => ['required', 'string', 'max:255']
        ];
    }
}
