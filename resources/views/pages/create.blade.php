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
                action="create"
                :resource-fields="{{ $resourceFields }}"
                :relational-fields="{{ $relationalFields }}"
                :validation-fields="{{ $validationFields }}"
                resource-prefix="{{ config('otter.path', 'otter') }}"
        ></form-component>
    </div>
@stop

@section("scripts")
    <script type="text/javascript">
        "use strict";
    </script>
@stop