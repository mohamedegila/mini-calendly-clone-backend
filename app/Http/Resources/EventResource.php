<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            "id"   => $this->id,
            "name" => $this->name,
            "slug" => $this->slug,
            "description" => $this->description,
            "start_date" => $this->start_date,
            "end_date" => $this->end_date,
            "start_time" => $this->start_time,
            "end_time" => $this->end_time,
            "duration" => $this->duration,
            "location" => $this->location,
            "link" => $this->link,
            "url" => "/events/". $this->manger->slug .'/' . $this->slug,
            "available_times" => $this->timeSteps
        ];
    }
}
