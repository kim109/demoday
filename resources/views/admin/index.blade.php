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
    <div class="container" id="admin">
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

        <div class="form-horizontal">
            <div class="form-group">
                <label for="supply" class="col-sm-3 col-md-2 control-label">개인별 지급 J-Coin</label>
                <div class="col-sm-7 col-md-8">
                    <input type="number" class="form-control" step="10" min="0" max="255" placeholder="개인별 지급 J-Coin" v-model="supply">
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-default" v-on:click="saveSupply">저장</button>
                </div>
            </div>
            <div class="form-group">
                <label for="capital" class="col-sm-3 col-md-2 control-label">실제 투자액 설정</label>
                <div class="col-sm-7 col-md-8">
                    <input type="number" class="form-control" step="1000" min="0" max="100000000" placeholder="실제 투자액" v-model="capital">
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-default" v-on:click="saveCapital">저장</button>
                </div>
            </div>
            <div class="form-group">
                <label for="capital" class="col-sm-3 col-md-2 control-label">모의투자 Open</label>
                <div class="col-sm-7">
                    <input type="checkbox" name="my-checkbox" checked>
                </div>
            </div>
        </div>

         <hr>

         <div class="row">
            <item id="2"></item>

            <div class="col-sm-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">신규 PT</div>
                    <div class="panel-body form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">발표 제목</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" placeholder="발표 제목" v-model="item.title">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">발표자 회사</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" placeholder="발표자 회사" v-model="item.company">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">발표자 이름</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" placeholder="발표자 이름" v-model="item.speaker">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">발표 내용 요약</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" v-model="item.description"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer text-right">
                        <button class="btn btn-success" v-on:click="storeItem">등록</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="{{ mix('js/admin.js') }}"></script>
</body>
</html>