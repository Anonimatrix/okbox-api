<?php

namespace App\Http\Resources\SearchAnalytic;

use Illuminate\Http\Resources\Json\JsonResource;

class SearchAnalyticResource extends JsonResource
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
            'keys' => $this->resource->getKeys(),
            'clicks' => $this->resource->getClicks(),
            'impressions' => $this->resource->getImpressions(),
            'ctr' => $this->resource->getCtr(),
            'position' => $this->resource->getPosition(),
        ];
    }
}
