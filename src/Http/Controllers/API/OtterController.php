<?php

namespace Poowf\Otter\Http\Controllers\API;

use Poowf\Otter\Otter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Poowf\Otter\Http\Controllers\Controller;

class OtterController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct();
        //        $resourceName = str_replace('api/otter/', '', $request->route()->uri);
        if (! app()->runningInConsole()) {
            //TODO: Retreiving the resource name like this means it's highly reliant on the singular and plural words of the model
            // Wondering if there is a way to decouple it.
            $this->resourceName = explode('.', $request->route()->getName())[2];
            $this->resourceNamespace = Otter::$otterResourceNamespace;
            $this->baseResourceName = Otter::getClassNameFromRouteName($this->resourceName);
            $this->resource = $this->resourceNamespace.$this->baseResourceName;
            $this->modelName = $this->resource::$model;
            $this->resourceRouteKeyName = $this->resource::$routeKeyName;
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $modelName = $this->modelName;
        //Instantiate new model instance
        $modelInstance = new $modelName;
        //Return an Otter resource of the model

        if ($request->has('resourceId') && $request->has('relationshipName') && $request->has('relationshipResourceName')) {
            $resourceId = $request->query('resourceId');
            $relationshipName = $request->query('relationshipName');
            $relationshipResourceName = $request->query('relationshipResourceName');

            $modelInstance = $modelInstance->findOrFail($resourceId);
            $relationshipResource = $this->resourceNamespace.Otter::getClassNameFromRouteName($relationshipResourceName);

            $data = $modelInstance->{$relationshipName}()->paginate(config('otter.pagination', 10));

            return $relationshipResource::collection($data);
        } else {
            return $this->resource::collection(($modelInstance)::paginate(config('otter.pagination', 10)));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $resource = $this->resource;
        $modelName = $this->modelName;
        $baseResourceName = $this->baseResourceName;
        //Instantiate new model instance
        $modelInstance = new $modelName;

        $validationRules = ($resource::validations() && $resource::validations()['server'] && $resource::validations()['server']['create']) ? $resource::validations()['server']['create'] : [];

        $validator = Validator::make($request->all(), $validationRules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => "Failed creating a new {$baseResourceName} resource",
                'errors' => $validator->messages(),
            ], 422);
        }

        $relationalFields = null;

        if ($request->has('relationalFields')) {
            $relationalFields = $request->input('relationalFields');
            $request->request->remove('relationalFields');
        }

        //Force filling of variables into model instance
        $modelInstance->forceFill($request->all());

        //Save model instance
        $modelInstance->save();

        if ($relationalFields) {
            foreach ($relationalFields as $relationalField) {
                $relationshipModel = $relationalField['relationshipModel'];
                $relationshipName = $relationalField['relationshipName'];
                $relationshipType = $relationalField['relationshipType'];
                $relationshipId = $relationalField['relationshipId'];

                if ($relationshipType === 'BelongsTo') {
                    $modelInstance->{$relationshipName}()->associate($relationshipId);
                } elseif ($relationshipType === 'BelongsToMany') {
                    $modelInstance->{$relationshipName}()->attach($relationshipId);
                }
            }

            //Save model instance
            $modelInstance->save();
        }

        //Return response
        return response()->json([
            'status' => 'success',
            'data' => new $this->resource($modelInstance),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param $modelInstance
     * @return \Illuminate\Http\Response
     */
    public function show($modelInstance)
    {
        //Retrieve the model instance
        $modelInstance = Otter::getModelInstance($modelInstance, $this->modelName, $this->resourceRouteKeyName);

        return new $this->resource($modelInstance);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $modelInstance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $modelInstance)
    {
        //Retrieve the model instance
        $resource = $this->resource;
        $baseResourceName = $this->baseResourceName;

        $validationRules = ($resource::validations() && $resource::validations()['server'] && $resource::validations()['server']['update']) ? $resource::validations()['server']['update'] : [];

        $validator = Validator::make($request->all(), $validationRules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => "Failed creating a new {$baseResourceName} resource",
                'errors' => $validator->messages(),
            ], 422);
        }

        //Cleanup request before saving data
        $request->request->remove('relations');
        $request->request->remove('route_key');

        $modelInstance = Otter::getModelInstance($modelInstance, $this->modelName, $this->resourceRouteKeyName);

        if ($request->has('relationalFields')) {
            $relationalFields = $request->input('relationalFields');
            $request->request->remove('relationalFields');

            foreach ($relationalFields as $relationalField) {
                $relationshipModel = $relationalField['relationshipModel'];
                $relationshipName = $relationalField['relationshipName'];
                $relationshipType = $relationalField['relationshipType'];
                $relationshipId = $relationalField['relationshipId'];

                if ($relationshipType === 'BelongsTo') {
                    $modelInstance->{$relationshipName}()->associate($relationshipId);
                } elseif ($relationshipType === 'BelongsToMany') {
                    $modelInstance->{$relationshipName}()->sync($relationshipId);
                }
            }

            $modelInstance->save();
        }
        
        $relationalForeignKeys = Otter::getRelationalForeignKeys($resource);

        foreach($relationalForeignKeys as $relationalForeignKey)
        {
            $request->request->remove($relationalForeignKey);
        }

        $modelInstance->forceFill($request->all());
        $modelInstance->save();

        return response()->json([
            'status' => 'success',
            'data' => new $this->resource($modelInstance),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $modelInstance
     * @return \Illuminate\Http\Response
     */
    public function destroy($modelInstance)
    {
        //Retrieve the model instance
        $modelInstance = Otter::getModelInstance($modelInstance, $this->modelName, $this->resourceRouteKeyName);
        $modelInstance->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }

    /**
     * Get all relational data from the OtterResource.
     *
     * @return \Illuminate\Http\Response
     */
    public function relational()
    {
        $resource = $this->resource;
        $relationalData = Otter::getRelationalData($resource);

        return response()->json([
            'data' => $relationalData,
        ]);
    }
}
