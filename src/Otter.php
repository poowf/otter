<?php

namespace Poowf\Otter;

use Closure;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

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
    public static function getResourceNames()
    {
        $directory = app_path('Otter/');
        $files = File::files($directory);
        $names = new Collection;

        foreach($files as $file)
        {
            $path = $file->getPathname();
            $class = str_replace('.php', '', $path);
            $baseResourceName = basename($class);
            $pluralName = str_plural(strtolower($baseResourceName));

            $names->push($pluralName);
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
}