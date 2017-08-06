<template>
    <div class="col-sm-6">
        <div class="panel panel-primary">
            <div class="panel-heading">{{this.index+1}}번 PT</div>
            <div class="panel-body form-horizontal">
                <div class="form-group form-group-sm">
                    <label class="col-sm-3 control-label">발표 제목</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" placeholder="발표 제목" v-model="item.title">
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label class="col-sm-3 control-label"><span class="hidden-sm">발표자 </span>회사</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" placeholder="발표자 회사" v-model="item.company">
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label class="col-sm-3 control-label"><span class="hidden-sm">발표자 </span>이름</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" placeholder="발표자 이름" v-model="item.speaker">
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label class="col-sm-3 control-label"><span class="hidden-sm">발표 </span>내용 요약</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" rows="3" v-model="item.description"></textarea>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label class="col-sm-3 control-label"><span class="hidden-sm">출석 </span>이벤트</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <input type="number" min="1" max="300" class="form-control" :readonly="!this.enableOpen" v-model="item.event_rank">
                            <span class="input-group-btn">
                                <button class="btn btn-sm btn-success" :disabled="!this.enableOpen" @click="eventOpen">
                                    <span class="hidden-sm">이벤트 </span>시작
                                </button>
                                <button class="btn btn-sm btn-default" :disabled="!this.enableResult" @click="eventResult">
                                    당첨자 확인
                                </button>
                            </span>
                        </div>
                        <span class="help-block" v-if="this.item.event_winner != null">
                            당첨자 : {{ this.item.event_winner }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="panel-footer text-right">
                <button class="btn btn-sm btn-info" @click="edit" :disabled="this.$parent.notReady">수정</button>
                <button class="btn btn-sm btn-danger" @click="remove" :disabled="this.$parent.notReady">삭제</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            item: {
                required: true
            },
            index: {
                required: true
            }
        },
        data: function() {
            return {
               title: null,
               company: null,
               speaker: null,
               description : null
            }
        },
        computed: {
            enableOpen: function () {
                return this.$parent.state == 'open' && !this.item.event_open;
            },
            enableResult: function () {
                return this.$parent.state == 'open' && this.item.event_open;
            }
        },
        methods: {
            edit: function(event) {
                axios.patch('admin/items/'+this.item.id, this.item)
                .then((response) => {
                    alert('수정 되었습니다.');
                })
                .catch((error) => {
                    console.log(error);
                });
            },
            remove: function(event) {
                if (window.confirm('['+this.item.title+'] 삭제 하시겠습니까?')) {
                    axios.delete('admin/items/'+this.item.id)
                    .then((response) => {
                        this.$emit('remove');
                    })
                    .catch((error) => {
                        console.log(error);
                    });
                }
            },
            eventOpen: function(event) {
                if (window.confirm('출석 이벤트를 시작 하시겠습니까?')) {
                    axios.patch('admin/items/'+this.item.id+'/event', {"open": true, "rank":this.item.event_rank})
                    .then((response) => {
                        this.item.event_open = true;
                        this.item.event_winner = null;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
                }
            },
            eventResult: function(event) {
                axios.patch('admin/items/'+this.item.id+'/event', {"open": false})
                    .then((response) => {
                        this.item.event_open = false;
                        this.item.event_winner = response.data.winner;

                        if (response.data.winner == null) {
                            alert('당첨자가 없습니다.');
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        }
    }
</script>