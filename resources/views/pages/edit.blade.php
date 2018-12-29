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
                    singular-resource-name="{{ $prettyResourceName }}"
                    resource-name="{{ $resourceName }}"
                    resource-id="{{ $resourceId }}"
                    action="edit"
                    :resource-fields="{{ $resourceFields }}"
                    :relational-fields="{{ $relationalFields }}"
            ></form-component>
        </div>
@stop

@section("scripts")
    <script type="text/javascript">
        "use strict";
    </script>
@stop