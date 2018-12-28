<?php

namespace Poowf\Otter\Http\Resources;

use Poowf\Otter\Otter;
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
        $transformed = parent::toArray($request);
        $transformed['route_key'] = $this->{parent::getRouteKeyName()};
        $transformed['relations'] = !empty($this->getRelationalData()) ? $this->getRelationalData() : null;
        $transformed['created_at'] = $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null;
        $transformed['updated_at'] = $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null;
        $transformed['deleted_at'] = $this->deleted_at ? $this->deleted_at->format('Y-m-d H:i:s') : null;
        
        return $transformed;
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
     * Get the relations used by the resource
     *
     * @return array
     */
    public function relations()
    {
        return [
        ];
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

    /**
     * Get the relational data and the relational type
     *
     * @return array
     */
    private function getRelationalData()
    {
        $relationalDataArray = [];
        
        foreach($this::relations() as $relationKey => $otterResourceName)
        {
            $otterResourceNamespace = 'App\\Otter\\';
            $otterResource = $otterResourceNamespace . $otterResourceName;

            $relation = [];
            $relation['relationshipType'] = str_replace('Illuminate\\Database\\Eloquent\\Relations\\', '', get_class($this->{$relationKey}()));
            $relation['otterResourceName'] = $otterResourceName;
            $relation['resourceUrlName'] = str_plural(strtolower(preg_replace('/\B([A-Z])/', '_$1', $otterResourceName)));
            $relation['resourceFields'] = Otter::getAvailableFields($otterResource);
            $relationalDataArray[$relationKey] = $relation;
        }

        return $relationalDataArray;
    }
}