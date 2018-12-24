<?php

namespace Poowf\Otter\Http\Controllers;

use Illuminate\Http\Request;
use Poowf\Otter\Otter;

class OtterViewController extends Controller
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
            $this->allResourceNames = Otter::getResourceNames();
        }
    }

    /**
     * Display the dashboard page.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
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
        $allResourceNames = $this->allResourceNames;
        $resourceName = $this->resourceName;
        $prettyResourceName = $this->baseResourceName;
        $resourceFields = json_encode($this->resource::fields());

        return view('otter::pages.index', compact('allResourceNames', 'prettyResourceName', 'resourceName', 'resourceFields'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
     * @param  \Illuminate\Database\Eloquent\Model $resourceInstance
     * @return \Illuminate\Http\Response
     */
    public function show($resourceInstance)
    {
        $allResourceNames = $this->allResourceNames;
        $resourceName = $this->resourceName;
        $prettyResourceName = $this->baseResourceName;
        $resourceFields = json_encode($this->resource::fields());
        $resourceId = $resourceInstance->id;

        return view('otter::pages.show', compact('allResourceNames', 'resourceId', 'prettyResourceName', 'resourceName', 'resourceFields'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Database\Eloquent\Model $resourceInstance
     * @return \Illuminate\Http\Response
     *
     */
    public function edit($resourceInstance)
    {
        $allResourceNames = $this->allResourceNames;
        $resourceName = $this->resourceName;
        $prettyResourceName = $this->baseResourceName;
        $resourceFields = json_encode($this->resource::fields());
        $resourceId = $resourceInstance->id;

        return view('otter::pages.edit', compact('allResourceNames', 'resourceId', 'prettyResourceName', 'resourceName', 'resourceFields'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  \Illuminate\Database\Eloquent\Model $resourceInstance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $resourceInstance)
    {
        //Intentionally Not Implemented
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model $resourceInstance
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($resourceInstance)
    {
        //Intentionally Not Implemented
    }
}