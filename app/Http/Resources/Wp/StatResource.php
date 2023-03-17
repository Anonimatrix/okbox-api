<?php

namespace App\Http\Resources\Wp;

use Illuminate\Http\Resources\Json\JsonResource;

class StatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = (object) $this->resource;

        return [
            'center' => $data->centro,
            'year' => $data->year,
            'month' => $data->month,
            'total_leads' => $data->cantidad_leads,
            'unique_total_lead' => $data->cantidad_leads_unicos,
        ];
    }
}
