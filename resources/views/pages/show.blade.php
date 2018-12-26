@extends("otter::layouts.app")

@section("head")
    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">
                {{ $prettyResourceName }}
            </h1>
        </div>
        <show-component
                resource-name="{{ $resourceName }}"
                pretty-resource-name="{{ $prettyResourceName }}"
                resource-id="{{ $resourceId }}"
                :resource-fields="{{ $resourceFields }}"
        ></show-component>
    </div>
@stop

@section("scripts")
    <script type="text/javascript">
        "use strict";
    </script>
@stop