<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DonorQuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'question' => $this->question,
            'type' => $this->type,
            'options' => $this->options,
            'placeholder' => $this->placeholder,
            'is_required' => $this->is_required ? true : false,
            'sort_order' => $this->sort_order,
            'size' => $this->size,
            'enabled' => $this->disabled_at ? false : true,
            'answer' => $this->answer,
        ];
    }
}
