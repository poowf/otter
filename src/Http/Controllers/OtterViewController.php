<?php

namespace Poowf\Otter\Http\Controllers;

use Illuminate\Http\Request;
use Poowf\Otter\Otter;

class OtterViewController extends Controller
{
    public function __construct(Request $request) {
        parent::__construct();
        //Check if application is running in console as the exploding of the route will fail if the app is running in console
        if(!app()->runningInConsole())
        {
            $this->resourceName = explode('.', $request->route()->getName())[2];
            $this->resourceNamespace = Otter::$otterResourceNamespace;
            $this->baseResourceName = Otter::getClassNameFromRouteName($this->resourceName);
            $this->resource = $this->resourceNamespace . $this->baseResourceName;
            $this->prettyResourceName = str_singular(ucwords(str_replace('_', ' ', $this->resourceName)));
            $this->allResourceNames = Otter::getResourceNames();
            /** @var TYPE_NAME $model */
            $this->modelName = ($request->is('otter')) ? null : $this->resource::$model;
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
        $prettyResourceName = $this->prettyResourceName;
        $resourceName = $this->resourceName;
        $resourceFields = json_encode(Otter::getAvailableFields($this->resource));

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
        $prettyResourceName = $this->prettyResourceName;
        $resourceName = $this->resourceName;
        $resource = $this->resource;
        $resourceFields = json_encode($resource::fields());
        $relationalFields = json_encode(Otter::getRelationalFields($resource));
        $validationFields = json_encode($resource::validations()['client']);

        return view('otter::pages.create', compact('allResourceNames', 'prettyResourceName', 'resourceName', 'resourceFields', 'relationalFields', 'validationFields'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
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
        $prettyResourceName = $this->prettyResourceName;
        $resourceName = $this->resourceName;
        $resourceFields = json_encode(Otter::getAvailableFields($this->resource));
        $resourceId = $modelInstance->{$modelInstance->getRouteKeyName()};

        return view('otter::pages.show', compact('allResourceNames', 'prettyResourceName', 'resourceId', 'resourceName', 'resourceFields'));
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
        $prettyResourceName = $this->prettyResourceName;
        $resourceName = $this->resourceName;
        $resource = $this->resource;
        $resourceFields = json_encode($resource::fields());
        $resourceId = $modelInstance->{$modelInstance->getRouteKeyName()};
        $relationalFields = json_encode(Otter::getRelationalFields($resource, $modelInstance));
        $validationFields = json_encode($resource::validations()['client']);

        return view('otter::pages.edit', compact('allResourceNames', 'prettyResourceName', 'resourceId', 'resourceName', 'resourceFields', 'relationalFields', 'validationFields'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  \Illuminate\Database\Eloquent\Model $modelInstance
     * @return void
     */
    public function update(Request $request, $modelInstance)
    {
        //Intentionally Not Implemented
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model $modelInstance
     * @return void
     */
    public function destroy($modelInstance)
    {
        //Intentionally Not Implemented
    }
}