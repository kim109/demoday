<template>
    <div class="col-sm-6">
        <div class="panel panel-primary">
            <div class="panel-heading">Panel heading without title</div>
            <div class="panel-body form-horizontal">
                <div class="form-group">
                    <label class="col-sm-3 control-label">발표 제목</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" placeholder="발표 제목" v-model="title">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">발표자 회사</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" placeholder="발표자 회사" v-model="company">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">발표자 이름</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" placeholder="발표자 이름" v-model="speaker">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">발표 내용 요약</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" v-model="description"></textarea>
                    </div>
                </div>
            </div>
            <div class="panel-footer text-right">
                <button class="btn btn-info" v-on:click="edit">수정</button>
                <button class="btn btn-danger" v-on:click="remove">삭제</button>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        props: ['id'],
        data: function() {
            return {
               title: null,
               company: null,
               speaker: null,
               description : null
            }
        },
        mounted() {
            axios.get('admin/items/'+this.id)
            .then((response) => {
                this.title = response.data.title;
                this.company = response.data.company;
                this.speaker = response.data.speaker;
                this.description = response.data.description;
            })
            .catch((error) => {
                console.log(error);
            });
        },
        methods: {
            edit: function(event) {
                axios.patch('admin/items/'+this.id, this.$data)
                .then((response) => {
                    alert('수정 되었습니다.');
                })
                .catch((error) => {
                    console.log(error);
                });
            },
            remove: function(event) {
                axios.delete('admin/items/'+this.id, this.$data)
                .then((response) => {
                    alert('삭제 되었습니다.');
                })
                .catch((error) => {
                    console.log(error);
                });
            }
        }
    }
</script>