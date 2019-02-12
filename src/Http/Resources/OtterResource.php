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
        $transformed['relations'] = ! empty($this->getRelationships()) ? $this->getRelationships() : null;
        $transformed['created_at'] = $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null;
        $transformed['updated_at'] = $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null;
        $transformed['deleted_at'] = $this->deleted_at ? $this->deleted_at->format('Y-m-d H:i:s') : null;

        return $transformed;
    }
    
    /**
     * Resource route key name.
     *
     */
    static $routeKeyName = 'id';

    /**
     * Get the fields and types used by the resource.
     *
     * @return array
     */
    public static function fields()
    {
        return [];
    }

    /**
     * Get the validations used by the resource.
     *
     * @return array
     */
    public static function validations()
    {
        return [
        ];
    }

    /**
     * Get the fields to be hidden in the index.
     *
     * @return array
     */
    public static function hidden()
    {
        return [];
    }

    /**
     * Get the relations used by the resource.
     *
     * @return array
     */
    public static function relations()
    {
        return [
        ];
    }

    /**
     * Get the relational data and the relational type.
     *
     * @return array
     */
    private function getRelationships()
    {
        return Otter::getRelationalFields($this, $this->resource);
    }
}
