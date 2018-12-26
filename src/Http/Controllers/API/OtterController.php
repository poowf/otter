<?php

namespace Poowf\Otter\Http\Controllers\API;

use Illuminate\Http\Request;
use Poowf\Otter\Otter;
use Poowf\Otter\Http\Controllers\Controller;

class OtterController extends Controller
{
    public function __construct(Request $request) {
        parent::__construct();
        //        $resourceName = str_replace('api/otter/', '', $request->route()->uri);
        if(!app()->runningInConsole())
        {
            $this->resourceName = explode('.', $request->route()->getName())[2];
            $this->resourceNamespace = 'App\\Otter\\';
            $this->baseResourceName = ucfirst(str_singular($this->resourceName));
            $this->resource = $this->resourceNamespace . $this->baseResourceName;
            /** @var TYPE_NAME $model */
            $this->modelName = $this->resource::$model;
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        /** @var TYPE_NAME $model */
        $modelName = $this->resource::$model;
        //Instantiate new model instance
        $modelInstance = new $modelName;

        //Return an Otter resource of the model
        return $this->resource::collection($modelInstance::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /** @var TYPE_NAME $model */
        $modelName = $this->resource::$model;
        //Instantiate new model instance
        $modelInstance = new $modelName;
        //Force filling of variables into model instance
        $modelInstance->forceFill($request->all());
        //Save model instance
        $modelInstance->save();

        //Return response
        return response()->json([
            'status' => 'success',
            new $this->resource($modelInstance),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($modelInstance)
    {
        //Retrieve the model instance
        $modelInstance = Otter::getModelInstance($modelInstance, $this->modelName);

        return new $this->resource($modelInstance);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $modelInstance)
    {
        //Retrieve the model instance
        $modelInstance = Otter::getModelInstance($modelInstance, $this->modelName);
        $modelInstance->fill($request->all());
        $modelInstance->save();

        return response()->json([
            'status' => 'success',
            new $this->resource($modelInstance),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($modelInstance)
    {
        //Retrieve the model instance
        $modelInstance = Otter::getModelInstance($modelInstance, $this->modelName);
        $modelInstance->delete();

        return response()->json([
            'status' => 'success'
        ]);
    }
}