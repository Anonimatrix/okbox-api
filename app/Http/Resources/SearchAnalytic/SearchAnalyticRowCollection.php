<?php

namespace App\Http\Resources\SearchAnalytic;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SearchAnalyticRowCollection extends ResourceCollection
{
    public $collects = SearchAnalyticResource::class;
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
