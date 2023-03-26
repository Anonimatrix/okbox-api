<?php

namespace App\Http\Resources\SpManager;

use Illuminate\Http\Resources\Json\JsonResource;

class SpManagerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'centers' => $this->centers
        ];
    }
}
