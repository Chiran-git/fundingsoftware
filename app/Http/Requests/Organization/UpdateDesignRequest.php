<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDesignRequest extends FormRequest
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
            'cover_image' => ['sometimes', 'image', 'dimensions:min_width=1000,min_height=300', 'max:2048'],
            'logo' => ['sometimes', 'image', 'dimensions:min_width=100,min_height=100', 'max:1048'],
            'primary_color' => ['required', 'alpha-num', 'max:6'],
            'secondary_color' => ['required', 'alpha-num', 'max:6'],
            'appeal_headline' => ['required'],
            'appeal_message' => ['required'],
            'appeal_photo' => ['sometimes', 'image', 'dimensions:min_width=100,min_height=100', 'max:1048'],
        ];
    }

    /**
     * Custom validation messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'cover_image.image' => 'The cover image must be an image (Only jpeg, png, bmp, gif, svg, or webp supported).',
            'logo.image' => 'The logo must be an image (Only jpeg, png, bmp, gif, svg, or webp supported).',
            'appeal_photo.image' => 'The appeal photo must be an image (Only jpeg, png, bmp, gif, svg, or webp supported).'
        ];
    }
}
