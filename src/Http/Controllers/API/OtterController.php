<?php

namespace Poowf\Otter\Http\Controllers\API;

use Illuminate\Http\Request;
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
        $modelInstance = new $modelName;

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
        $modelInstance = new $modelName;
        $modelInstance->forceFill($request->all());
        $modelInstance->save();

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
        /** @var TYPE_NAME $model */
        $modelName = $this->resource::$model;
//        $modelInstance = $modelName::findOrFail($modelInstance)->first();


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
        /** @var TYPE_NAME $model */
        $modelName = $this->resource::$model;
//        $modelInstance = $modelName::findOrFail($modelInstance)->first();
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
        /** @var TYPE_NAME $model */
        $modelName = $this->resource::$model;
//        $modelInstance = $modelName::findOrFail($modelInstance)->first();
        $modelInstance->delete();

        return response()->json([
            'status' => 'success'
        ]);
    }
}