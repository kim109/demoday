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
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="supply" class="col-sm-3 col-md-4 control-label">개인별 지급 J-Coin</label>
                        <div class="col-sm-9 col-md-8">
                            <div class="input-group">
                                <input type="number" class="form-control" step="10" min="0" max="255" placeholder="개인별 지급 J-Coin" v-model="supply" :readonly="notReady">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" @click="saveSetting('supply')" :disabled="notReady">저장</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="capital" class="col-sm-3 col-md-4 control-label">실제 투자액 설정</label>
                        <div class="col-sm-9 col-md-8">
                            <div class="input-group">
                                <input type="number" class="form-control" step="1000" min="0" max="100000000" placeholder="실제 투자액" v-model="capital" :readonly="notReady">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" @click="saveSetting('capital')" :disabled="notReady">저장</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="supply" class="col-sm-3 col-md-4 control-label">전문가 아이디</label>
                        <div class="col-sm-9 col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control" v-model="experts" :readonly="notReady">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" @click="saveSetting('experts')" :disabled="notReady">저장</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="capital" class="col-sm-3 col-md-4 control-label">전문가 투자 배수</label>
                        <div class="col-sm-9 col-md-8">
                            <div class="input-group">
                                <input type="number" class="form-control" min="1" max="10" v-model="multiple" :readonly="notReady">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" @click="saveSetting('multiple')" :disabled="notReady">저장</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="capital" class="col-sm-3 col-md-4 control-label">투자 진행 상태</label>
                        <div class="col-sm-9 col-md-8">
                            <div class="input-group">
                                <select class="form-control" id="state">
                                    <option value="ready" :selected="state == 'ready'">준비중</option>
                                    <option value="open" :selected="state == 'open'">진행중</option>
                                    <option value="close" :selected="state == 'close'" :disabled="state == 'ready'">마감</option>
                                </select>
                                <span class="input-group-btn">
                                    <button class="btn btn-default" @click="saveState">저장</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" v-if="state === 'close'">
                    <div class="form-group">
                        <label for="capital" class="col-sm-3 col-md-4 control-label">모의 투자 결과 조회</label>
                        <div class="col-sm-9 col-md-8">
                            <button class="btn btn-primary" @click="showResult">결과 조회</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

         <hr>

         <div class="row">
            <item v-for="(item, index) in items" :key="item.id" :item="item" :index="index" v-on:remove="removeItem(index)"></item>

            <div class="col-sm-6"  v-if="state === 'ready'">
                <div class="panel panel-primary">
                    <div class="panel-heading">신규 PT</div>
                    <div class="panel-body form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">발표 제목</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control input-sm" placeholder="발표 제목" v-model="item.title">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">발표자 회사</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control input-sm" placeholder="발표자 회사" v-model="item.company">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">발표자 이름</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control input-sm" placeholder="발표자 이름" v-model="item.speaker">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">발표 내용 요약</label>
                            <div class="col-sm-9">
                                <textarea class="form-control input-sm" v-model="item.description"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer text-right">
                        <button class="btn btn-sm btn-success" @click="storeItem">등록</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">모의 투자 결과</h4>
                </div>
                <div class="modal-body">
                    <result-grid></result-grid>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="{{ mix('js/admin.js') }}"></script>
</body>
</html>