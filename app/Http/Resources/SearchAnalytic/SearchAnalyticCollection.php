<?php

namespace App\Http\Resources\SearchAnalytic;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SearchAnalyticCollection extends ResourceCollection
{
    public $collects = SearchAnalyticRowCollection::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function($item, $key) use ($request){
            return [
                'site' => $key,
                'rows' => $item->toArray($request)
            ];
        });
    }
}
