<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Advert extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'price' => $this->price,
            'photo' => strstr($this->photo, ',', true),
            $this->mergeWhen($request->filled('fields'), [
                'description' => $this->description,
                'photos' => explode(',', $this->photo)
            ])
        ];
    }
}
