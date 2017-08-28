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
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#top-navbar" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/admin">T.J.D 모의투자 Admin</a>
                </div>

                <div class="collapse navbar-collapse" id="top-navbar">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="#" @click="reset">
                                <span class="glyphicon glyphicon-erase" aria-hidden="true"></span> Reset
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}" onclick="document.getElementById('logout').submit(); return false;">
                                <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> 로그아웃
                            </a>
                        </li>
                    </ul>
                    <form id="logout" method="post" action="{{ route('logout') }}">
                        {{ csrf_field() }}
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
                                <input type="number" class="form-control" step="10" min="0" max="255" placeholder="개인별 지급 J-Coin" v-model.number="supply" :readonly="notReady">
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
                                <input type="number" class="form-control" step="1000" min="0" max="100000000" placeholder="실제 투자액" v-model.number="capital" :readonly="notReady">
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
                                <v-select v-model="experts" multiple :on-search="getExperts" :options="expertOptions" :searchable="!notReady"></v-select>
                                <span class="input-group-btn">
                                    <button class="btn btn-default" @click="saveSetting('experts')" :disabled="notReady">저장</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="capital" class="col-sm-3 col-md-4 control-label">전문가 할당 비율(%)</label>
                        <div class="col-sm-9 col-md-8">
                            <div class="input-group">
                                <input type="number" class="form-control" min="1" max="99" v-model.number="ratio" :readonly="notReady">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" @click="saveSetting('ratio')" :disabled="notReady">저장</button>
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
                                <select class="form-control" id="status">
                                    <option value="ready" :selected="status == 'ready'">준비중</option>
                                    <option value="open" :selected="status == 'open'">진행중</option>
                                    <option value="close" :selected="status == 'close'" :disabled="status == 'ready'">마감</option>
                                </select>
                                <span class="input-group-btn">
                                    <button class="btn btn-default" @click="saveStatus">저장</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" v-if="status === 'close'">
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

            <div class="col-sm-6"  v-if="status === 'ready'">
                <div class="panel panel-primary">
                    <div class="panel-heading">신규 PT</div>
                    <div class="panel-body form-horizontal">
                        <div class="form-group form-group-sm">
                            <label class="col-sm-3 control-label">발표 제목</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control input-sm" placeholder="발표 제목" v-model="item.title">
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label class="col-sm-3 control-label"><span class="hidden-sm">발표자 </span>회사</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control input-sm" placeholder="발표자 회사" v-model="item.company">
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label class="col-sm-3 control-label"><span class="hidden-sm">발표자 </span>이름</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control input-sm" placeholder="발표자 이름" v-model="item.speaker">
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label class="col-sm-3 control-label"><span class="hidden-sm">발표 </span>내용 요약</label>
                            <div class="col-sm-9">
                                <textarea class="form-control input-sm" rows="3" v-model="item.description"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer text-right">
                        <button class="btn btn-sm btn-success" @click="storeItem">등록</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="modal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">모의 투자 결과</h4>
                    </div>
                    <div class="modal-body">
                        <transition name="result-fade" mode="out-in">
                            <result-grid v-if="results != null && selectedResult == null" v-on:detail="showResultDetail"></result-grid>
                            <result-detail-grid  v-if="selectedResult != null" v-on:overview="selectedResult = null" :result="selectedResult"></result-detail-grid>
                        </transition>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="{{ mix('js/admin.js') }}"></script>
</body>
</html>
