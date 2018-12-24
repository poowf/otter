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
        <form-component
                resource-name="{{ $resourceName }}"
                pretty-resource-name="{{ $prettyResourceName }}"
                action="create"
                :resource-fields="{{ $resourceFields }}"
        ></form-component>
    </div>
@stop

@section("scripts")
    <script type="text/javascript">
        "use strict";
        require(['jquery'], function($) {

        });
    </script>
@stop