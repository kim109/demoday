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
                            <input type="number" min="1" max="300" class="form-control">
                            <span class="input-group-btn">
                                <button class="btn btn-sm btn-success"><span class="hidden-sm">이벤트 </span>시작</button>
                                <button class="btn btn-sm btn-default">당첨자 확인</button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer text-right">
                <button class="btn btn-sm btn-info" v-on:click="edit">수정</button>
                <button class="btn btn-sm btn-danger" v-on:click="remove">삭제</button>
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
                axios.delete('admin/items/'+this.item.id)
                .then((response) => {
                    alert('삭제 되었습니다.');
                    this.$emit('remove');
                })
                .catch((error) => {
                    console.log(error);
                });
            }
        }
    }
</script>