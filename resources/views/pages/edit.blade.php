@extends("otter::layouts.app")

@section("head")
    <style>
    </style>
@stop

@section("content")
            <div class="page-header">
                <h1 class="page-title">
                    {{ $prettyResourceName }}
                </h1>
            </div>
            <form-component
                    singular-resource-name="{{ $prettyResourceName }}"
                    resource-name="{{ $resourceName }}"
                    resource-id="{{ $resourceId }}"
                    action="update"
                    :resource-fields="{{ $resourceFields }}"
                    :relational-fields="{{ $relationalFields }}"
                    :validation-fields="{{ $validationFields }}"
                    path-prefix="{{ config('otter.path', 'otter') }}"
            ></form-component>
@stop

@section("scripts")
    <script type="text/javascript">
        "use strict";
    </script>
@stop
