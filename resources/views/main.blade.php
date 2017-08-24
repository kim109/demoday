<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="theme-color" content="#E95420">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ mix('/css/agent.css') }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <div class="left" v-if="$route.name != 'list'">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true" @click="$router.go(-1)"></span>
                    </div>
                    <div class="right" v-else>
                        <span class="glyphicon glyphicon-log-out" aria-hidden="true" @click="logout"></span>
                        <form id="logout" method="post" action="{{ route('logout') }}">{{ csrf_field() }}</form>
                    </div>

                    <div class="center">@{{ title }}</div>
                </div>
            </div>
        </nav>

        <router-view></router-view>
    </div>

    <script src="{{ mix('/js/agent.js') }}"></script>
</body>
</html>