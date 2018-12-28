<?php

namespace Poowf\Otter;

use Closure;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Poowf\Otter\Http\Resources\OtterResource;

class Otter
{
    /**
     * The callback that should be used to authenticate Otter users.
     *
     * @var \Closure
     */
    public static $authUsing;

    /**
     * Determine if the given request can access the Otter dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function check($request)
    {
        return (static::$authUsing ?: function () {
            return app()->environment('local');
        })($request);
    }

    /**
     * Set the callback that should be used to authenticate Otter users.
     *
     * @param  \Closure  $callback
     * @return static
     */
    public static function auth(Closure $callback)
    {
        static::$authUsing = $callback;

        return new static;
    }

    /**
     * Retrieve the Names of the Otter Resources
     *
     * @param  \Closure  $callback
     * @return static
     */
    public static function getResourceNames($pretty = false)
    {
        $directory = app_path('Otter/');
        $files = File::files($directory);
        $names = new Collection;

        foreach($files as $file)
        {
            $path = $file->getPathname();
            $class = str_replace('.php', '', $path);
            $baseResourceName = basename($class);

            $resourceName = ($pretty) ? str_plural(preg_replace('/\B([A-Z])/', ' $1', $baseResourceName)) : str_plural(strtolower(preg_replace('/\B([A-Z])/', '_$1', $baseResourceName)));

            $names->push($resourceName);
        }

        return $names;
    }

    /**
     * Retrieve the users's gravatar photo
     *
     * @return string
     */
    public static function getGravatarLink($email)
    {
        $hash = md5(strtolower(trim($email)));

        return "//www.gravatar.com/avatar/$hash";
    }

    /**
     * Retrieve the model instance
     * This method checks if the object is an instance of the model and if it is not, 
     * it will take the object as the primary key of the model and retrieve it
     *
     * @param  \Closure  $callback
     * @return static
     */
    public static function getModelInstance($object, $modelName)
    {
       return ($object instanceof $modelName) ? $object  : $modelName::findOrFail($object);
    }

    /**
     * Retrieve all the fields that are not hidden in the resource collection
     *
     * @param  OtterResource $otterResource
     * @return array
     */
    public static function getAvailableFields($otterResource)
    {
        return array_diff_key($otterResource::fields(), array_flip($otterResource::hidden()));
    }

    /**
     * Retrieve all the fields that are relational
     *
     * @param  OtterResource $otterResource
     * @return array
     */
    public static function getRelationalFields($otterResource)
    {
        $relationalDataArray = [];

        $modelInstance = new $otterResource::$model;

        foreach($otterResource::relations() as $relationKey => $otterResourceName)
        {
            $otterResourceNamespace = 'App\\Otter\\';
            $otterRelationalResource = $otterResourceNamespace . $otterResourceName;
            $relationshipType = str_replace('Illuminate\\Database\\Eloquent\\Relations\\', '', get_class($modelInstance->{$relationKey}()));

            $relation = [];
            $relation['relationshipName'] = $relationKey;
            $relation['relationshipType'] = $relationshipType;
            $relation['relationshipModel'] = $otterRelationalResource::$model;
            $relation['resourceName'] = str_plural(strtolower(preg_replace('/\B([A-Z])/', '_$1', $otterResourceName)));
            $relation['resourceTitle'] = $otterRelationalResource::$title;

            $relationalDataArray[$relationKey] = $relation;
        }

        return $relationalDataArray;
    }

    public static function getRelationalData($otterResource)
    {
        $relationalDataArray = [];

        $modelInstance = new $otterResource::$model;

        foreach($otterResource::relations() as $relationKey => $otterResourceName)
        {
            $otterResourceNamespace = 'App\\Otter\\';
            $otterRelationalResource = $otterResourceNamespace . $otterResourceName;
            $relationalDataArray[$relationKey] = $otterRelationalResource::collection((new $otterRelationalResource::$model)::all());
        }

        return $relationalDataArray;
    }

}