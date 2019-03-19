@extends("otter::layouts.app")

@section("head")
    <style>
    </style>
@stop

@section("content")
    <div class="my-3 my-md-5">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">
                    {{ $prettyResourceName }}
                </h1>
            </div>
            <table-component
                    resource-name="{{ $resourceName }}"
                    :resource-fields="{{ $resourceFields }}"
                    path-prefix="{{ config('otter.path', 'otter') }}"
            ></table-component>
        </div>
    </div>
@stop

@section("scripts")
    <script type="text/javascript">
        "use strict";
    </script>
@stop