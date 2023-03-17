<?php

namespace App\Http\Resources\Wp;

use Illuminate\Http\Resources\Json\ResourceCollection;

class StatsCollection extends ResourceCollection
{
    public $collects = StatResource::class;
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection;
    }
}
