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
            //TODO: Retreiving the resource name like this means it's highly reliant on the singular and plural words of the model
            // Wondering if there is a way to decouple it.
            $this->resourceName = explode('.', $request->route()->getName())[2];
            $this->resourceNamespace = 'App\\Otter\\';
            //TODO: This is ugly, try to look for an alternative way to transform the string.
            $this->baseResourceName = Otter::getClassNameFromRouteName($this->resourceName);
            $this->resource = $this->resourceNamespace . $this->baseResourceName;
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
        $modelName = $this->modelName;
        //Instantiate new model instance
        $modelInstance = new $modelName;
        //Return an Otter resource of the model
        return $this->resource::collection(($modelInstance)::paginate(config('otter.pagination', 10)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $modelName = $this->modelName;
        //Instantiate new model instance
        $modelInstance = new $modelName;

        if($request->input('relationalFields'))
        {
            $relationalFields = $request->input('relationalFields');
            $request->request->remove('relationalFields');

            foreach($relationalFields as $relationalField)
            {
                $relationshipModel = $relationalField['relationshipModel'];
                $relationshipName = $relationalField['relationshipName'];
                $relationshipType = $relationalField['relationshipType'];
                $relationshipId = $relationalField['relationshipId'];

                if($relationshipType === 'BelongsTo')
                {
                    $modelInstance->{$relationshipName}()->associate($relationshipId);
                }
                elseif($relationshipType === 'BelongsToMany')
                {
                    $modelInstance->{$relationshipName}()->attach($relationshipId);
                }
            }
        }

        //Force filling of variables into model instance
        $modelInstance->forceFill($request->all());
        //Save model instance
        $modelInstance->save();
        
        //Return response
        return response()->json([
            'status' => 'success',
            'data' => new $this->resource($modelInstance),
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

        if($request->input('relationalFields'))
        {
            $relationalFields = $request->input('relationalFields');
            $request->request->remove('relationalFields');

            foreach($relationalFields as $relationalField)
            {
                $relationshipModel = $relationalField['relationshipModel'];
                $relationshipName = $relationalField['relationshipName'];
                $relationshipType = $relationalField['relationshipType'];
                $relationshipId = $relationalField['relationshipId'];

                if($relationshipType === 'BelongsTo')
                {
                    $modelInstance->{$relationshipName}()->associate($relationshipId);
                }
                elseif($relationshipType === 'BelongsToMany')
                {
                    $modelInstance->{$relationshipName}()->sync($relationshipId);
                }
            }
        }

        $modelInstance->fill($request->all());
        $modelInstance->save();

        return response()->json([
            'status' => 'success',
            'data' => new $this->resource($modelInstance),
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

    /**
     * Get all relational data in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function relational()
    {
        $resource = $this->resource;
        $relationalData = Otter::getRelationalData($resource);

        return response()->json([
            'status' => 'success',
            'data' => $relationalData
        ]);
    }
}