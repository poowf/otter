<?php

namespace Poowf\Otter\Http\Controllers;

use Illuminate\Http\Request;
use Poowf\Otter\Otter;

class OtterViewController extends Controller
{
    public function __construct(Request $request) {
        parent::__construct();
        //        $resourceName = str_replace('api/otter/', '', $request->route()->uri);
        //Check if application is running in console as the exploding of the route will fail if the app is running in console
        if(!app()->runningInConsole())
        {
            $this->resourceName = explode('.', $request->route()->getName())[2];
            $this->resourceNamespace = 'App\\Otter\\';
            //TODO: This is ugly, try to look for an alternative way to transform the string.
            $this->baseResourceName = str_replace(' ', '', str_singular(ucwords(str_replace('_', ' ', $this->resourceName))));
            $this->resource = $this->resourceNamespace . $this->baseResourceName;
            $this->allResourceNames = Otter::getResourceNames();
            /** @var TYPE_NAME $model */
            $this->modelName = (route_is('web.otter.dashboard')) ? null : $this->resource::$model;
        }
    }

    /**
     * Display the dashboard page.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        //Retrieve all the otter resource names that are available
        $allResourceNames = $this->allResourceNames;

        return view('otter::pages.dashboard', compact('allResourceNames'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        //Retrieve all the otter resource names that are available
        $allResourceNames = $this->allResourceNames;
        $resourceName = $this->resourceName;
        $prettyResourceName = $this->baseResourceName;
        $resourceFields = json_encode($this->getAvailableFields($this->resource::fields(), $this->resource::hidden()));

        return view('otter::pages.index', compact('allResourceNames', 'prettyResourceName', 'resourceName', 'resourceFields'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Retrieve all the otter resource names that are available
        $allResourceNames = $this->allResourceNames;
        $resourceName = $this->resourceName;
        $prettyResourceName = $this->baseResourceName;
        $resourceFields = json_encode($this->resource::fields());

        return view('otter::pages.create', compact('allResourceNames', 'prettyResourceName', 'resourceName', 'resourceFields'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Intentionally Not Implemented
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Database\Eloquent\Model $modelInstance
     * @return \Illuminate\Http\Response
     */
    public function show($modelInstance)
    {
        //Retrieve the model instance
        $modelInstance = Otter::getModelInstance($modelInstance, $this->modelName);
        //Retrieve all the otter resource names that are available
        $allResourceNames = $this->allResourceNames;
        $resourceName = $this->resourceName;
        $prettyResourceName = $this->baseResourceName;
        $resourceFields = json_encode($this->getAvailableFields($this->resource::fields(), $this->resource::hidden()));
        $resourceId = $modelInstance->{$modelInstance->getRouteKeyName()};


        return view('otter::pages.show', compact('allResourceNames', 'resourceId', 'prettyResourceName', 'resourceName', 'resourceFields'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Database\Eloquent\Model $modelInstance
     * @return \Illuminate\Http\Response
     *
     */
    public function edit($modelInstance)
    {
        //Retrieve the model instance
        $modelInstance = Otter::getModelInstance($modelInstance, $this->modelName);
        //Retrieve all the otter resource names that are available
        $allResourceNames = $this->allResourceNames;
        $resourceName = $this->resourceName;
        $prettyResourceName = $this->baseResourceName;
        $resourceFields = json_encode($this->resource::fields());
        $resourceId = $modelInstance->{$modelInstance->getRouteKeyName()};

        return view('otter::pages.edit', compact('allResourceNames', 'resourceId', 'prettyResourceName', 'resourceName', 'resourceFields'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  \Illuminate\Database\Eloquent\Model $modelInstance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $modelInstance)
    {
        //Intentionally Not Implemented
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model $modelInstance
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($modelInstance)
    {
        //Intentionally Not Implemented
    }

    /**
     * Retrieve all the fields that are not hidden in the resource collection
     *
     * @param  array $fields
     * @param  array $hidden
     * @return array
     */
    public function getAvailableFields($fields, $hidden)
    {
        return array_diff_key($fields, array_flip($hidden));
    }
}