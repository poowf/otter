<!doctype html>
<html lang="{{ config('app.locale') }}" dir="ltr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta http-equiv="Content-Language" content="en" />
        <meta name="msapplication-TileColor" content="#2d89ef">
        <meta name="theme-color" content="#4188c9">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="HandheldFriendly" content="True">
        <meta name="MobileOptimized" content="320">
        <link rel="icon" href="{{ asset('vendor/otter/favicon.ico') }}" type="image/x-icon"/>
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('vendor/otter/favicon.ico') }}" />
        <title>Otter</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,300i,400,400i,500,500i,600,600i,700,700i&amp;subset=latin-ext">
        <script src="{{ asset('vendor/otter/assets/js/require.min.js') }}"></script>
        <script>
            requirejs.config({
                baseUrl: '/vendor/otter/',
            });
        </script>
        <!-- Dashboard Core -->
        <link href="{{ asset('vendor/otter/assets/css/dashboard.css') }}" rel="stylesheet" />
        <script src="{{ asset('vendor/otter/assets/js/dashboard.js') }}"></script>
        <!-- c3.js Charts Plugin -->
        <link href="{{ asset('vendor/otter/assets/plugins/charts-c3/plugin.css') }}" rel="stylesheet" />
        <script src="{{ asset('vendor/otter/assets/plugins/charts-c3/plugin.js') }}"></script>
        <!-- Google Maps Plugin -->
        <link href="{{ asset('vendor/otter/assets/plugins/maps-google/plugin.css') }}" rel="stylesheet" />
        <script src="{{ asset('vendor/otter/assets/plugins/maps-google/plugin.js') }}"></script>
        <!-- Input Mask Plugin -->
        <script src="{{ asset('vendor/otter/assets/plugins/input-mask/plugin.js') }}"></script>
        <style>
            .sidebar {
                background: #fff;
                border-right: 1px solid rgba(0, 40, 100, 0.12);
            }
            .sidebar.fixed {
                display: none;
            }

            .sidebar .nav-tabs .nav-item {
                width: 100%;
                display: block;
            }

            .sidebar .nav-tabs .nav-link {
                border-bottom: 1px solid rgba(0, 40, 100, 0.12);
            }

            a[data-toggle="collapse"] {
                position: relative;
            }

            .sidebar .dropdown-toggle::after {
                display: block;
                position: absolute;
                top: 50%;
                right: 20px;
                transform: translateY(-50%);
            }

            .sidebar .dropdown-toggle::after {
                display: inline-block;
                width: 0;
                height: 0;
                margin-left: .255em;
                vertical-align: .255em;
                content: "";
                border-top: .3em solid;
                border-right: .3em solid transparent;
                border-bottom: 0;
                border-left: .3em solid transparent;
            }

            .action-container {
                position: absolute;
                right: 20px;
            }

            @media (min-width: 992px) {
                .fixed {
                    flex: 0 0 200px;
                    min-height: 100vh;
                }
                .col .fluid {
                    min-height: 100vh;
                }
                .sidebar.fixed {
                    display: block;
                }
                .header .header-brand {
                    display: none;
                }
            }

            .flex-grow {
                flex:1;
            }
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
            @yield("scripts")
        </div>
    </body>
</html>