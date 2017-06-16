<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ mix('/css/admin.css') }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
</head>
<body>
    <div class="container">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#reset-button" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/admin">T.J.D 모의투자 Admin</a>
                </div>

                <div class="collapse navbar-collapse" id="reset-button">
                    <form class="navbar-right" action="admin/reset" method="post" id="reset-form">
                        {!! csrf_field() !!}
                        <input type="hidden" name="_method" value="delete">
                        <button type="button" class="btn btn-sm btn-danger navbar-btn"><i class="fa fa-repeat" aria-hidden="true"></i> Reset</button>
                    </form>
                </div>
            </div>
        </nav>
    </div>
</body>
</html>