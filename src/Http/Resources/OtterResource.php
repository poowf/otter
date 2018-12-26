<?php

namespace Poowf\Otter\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OtterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $array = parent::toArray($request);
        $array['route_key'] = $this->{parent::getRouteKeyName()};

        return $array;
    }

    /**
     * Get the fields and types used by the resource
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }

    /**
     * Get the fields to be hidden in the index
     *
     * @return array
     */
    public function hidden()
    {
        return [];
    }
}