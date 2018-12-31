<!doctype html>
<html lang="{{ config('app.locale') }}" dir="ltr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta http-equiv="Content-Language" content="en" />
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('vendor/otter/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('vendor/otter/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('vendor/otter/favicon-16x16.png') }}">
        <link rel="manifest" href="{{ asset('vendor/otter/site.webmanifest') }}">
        <meta name="apple-mobile-web-app-title" content="Otter">
        <meta name="application-name" content="Otter">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="theme-color" content="#ffffff">
        <title>Otter</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,300i,400,400i,500,500i,600,600i,700,700i&amp;subset=latin-ext">
        <link rel="stylesheet" href="{{ asset(mix('assets/css/tabler.css', 'vendor/otter')) }}">
        <link rel="stylesheet" href="{{ asset(mix((\Poowf\Otter\Otter::$useDarkTheme) ? 'assets/css/app-dark.css' : 'assets/css/app.css', 'vendor/otter')) }}">

        <style>

        </style>
        @yield("head")
    </head>
    <body class="">
        <div class="page container-fluid">
            <div id="app" class="page-main row h-100">
                @include("otter::partials/sidebar")

                <!-- center content -->
                <div class="col fluid d-flex flex-column px-0">
                    @include("otter::partials/header")
                    <!-- main content -->
                    <div class="flex-grow">
                        @yield("content")
                    </div>

                    @include("otter::partials/footer")
                </div>
            </div>

            <script src="{{ asset(mix('assets/js/manifest.js', 'vendor/otter')) }}"></script>
            <script src="{{ asset(mix('assets/js/vendor.js', 'vendor/otter')) }}"></script>
            <script src="{{ asset(mix('assets/js/bootstrap.js', 'vendor/otter')) }}"></script>
            <script src="{{ asset(mix('assets/js/app.js', 'vendor/otter')) }}"></script>
            <script src="{{ asset(mix('assets/js/core.js', 'vendor/otter')) }}"></script>
            @yield("scripts")
        </div>
    </body>
</html>