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

        <h4>모의 투자 환경 설정</h4>

        <div class="form-horizontal" id="setting">
            <div class="form-group">
                <label for="supply" class="col-sm-2 control-label">개인별 지급 J-Coin</label>
                <div class="col-sm-8">
                    <input type="number" class="form-control" step="10" min="0" max="255" placeholder="개인별 지급 J-Coin" v-model="supply">
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-default" v-on:click="saveSupply">저장</button>
                </div>
            </div>
            <div class="form-group">
                <label for="capital" class="col-sm-2 control-label">실제 투자액 설정</label>
                <div class="col-sm-8">
                    <input type="number" class="form-control" step="1000" min="0" max="100000000" placeholder="실제 투자액" v-model="capital">
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-default" v-on:click="saveCapital">저장</button>
                </div>
            </div>
            <div class="form-group">
                <label for="capital" class="col-sm-2 control-label">모의투자 Open</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" id="capital" placeholder="실제 투자액">
                </div>
            </div>
        </div>

        <hr>
    </div>

    <script type="text/javascript" src="{{ mix('js/admin.js') }}"></script>
</body>
</html>