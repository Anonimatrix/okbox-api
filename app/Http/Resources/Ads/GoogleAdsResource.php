<?php

namespace App\Http\Resources\Ads;

use Illuminate\Http\Resources\Json\JsonResource;

class GoogleAdsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $item = (object) $this->resource;
        
        return [
            'Ad Group' => $item->adGroup,
            'clicks' => $item->metrics->getClicks(),
            'impressions' => $item->metrics->getImpressions(),
            'ctr' => $item->metrics->getCtr(),
            'average_cpc' => $item->metrics->getAverageCpc()
        ];
    }
}
