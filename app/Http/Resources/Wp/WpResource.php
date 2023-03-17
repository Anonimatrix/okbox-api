<?php

namespace App\Http\Resources\Wp;

use Illuminate\Http\Resources\Json\JsonResource;

class WpResource extends JsonResource
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
            'status' => $this->estado,
            'message' => $this->mensaje,
            'stats' => new StatsCollection($this->estadisticas),
        ];
    }
}
