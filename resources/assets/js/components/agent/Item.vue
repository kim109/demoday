<template>
    <transition name="slide">
        <div>
            <div class="container">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <dl class="dl-horizontal">
                            <dt>회 사</dt>
                            <dd>{{ item.company }}</dd>
                        </dl>

                        <dl class="dl-horizontal">
                            <dt>발표자</dt>
                            <dd>{{ item.speaker }}</dd>
                        </dl>

                        <dl class="dl-horizontal">
                            <dt>내 용</dt>
                            <dd>{{ item.description }}</dd>
                        </dl>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">J-Coin 투자금액</div>
                    <div class="panel-body">
                        <h5 class="text-center">잔여 코인 : {{ balance }} / 투자 금액 : <strong>{{ investment }}</strong></h5>
                        <p class="small text-center text-danger"v-if="this.item.company == this.$store.state.user.company">
                            * 같은회사 투자금 부여는 1코인으로 제한
                        </p>
                        <div class="slider">
                            <vue-slider ref="slider" v-model.number="investment" :disabled="!sliderEnable" :min="1" :max="max" :dot-size="25" tooltip="false"></vue-slider>
                        </div>
                    </div>
                    <div class="panel-footer text-right">
                        <template v-if="$store.state.status != 'close'">
                            <button type="button" class="btn btn-default btn-sm" @click="event" v-if="eventEnable">상품 응모</button>
                            <button type="button" class="btn btn-primary btn-sm" @click="save" v-if="saveEnable" :disabled="investment == this.item.investment">저장</button>
                        </template>
                        <template v-else>
                            투자가 마감되었습니다.
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    import vueSlider from 'vue-slider-component'

    export default {
        components: { vueSlider },
        data: function() {
            return {
                investment: 0,
                init: true
            }
        },
        computed: {
            eventEnable() {
                return this.init && this.item.investment == 0;
            },
            saveEnable() {
                return !this.init || this.item.investment != 0;
            },
            sliderEnable() {
                return this.saveEnable && this.$store.state.status != 'close';
            },
            item() {
                let index = this.$route.params.index;
                return this.$store.state.items[index];
            },
            balance() {
                let consume = 0;
                this.$store.state.items.forEach((item) => {
                    if (item != this.item) {
                        consume += parseInt(item.investment);
                    }
                });
                return this.$store.state.coin-consume-this.investment;
            },
            max: function() {
                if (this.item.company != this.$store.state.user.company) {
                    let half = Math.round(this.$store.state.coin / 2);

                    let balance = this.$store.state.coin;
                    this.$store.state.items.forEach((item) => {
                        if (item != this.item) {
                            balance -= parseInt(item.investment);
                        }
                    });

                    return (balance > half) ? half : balance;
                } else {
                    return 1;
                }
            }
        },
        methods: {
            save() {
                if (window.confirm('투자금을 '+this.investment+'으로 설정하시겠습니까?')) {
                    this.$http.post('items/'+this.item.id+'/investment', {'investment': this.investment})
                        .then((response) => {
                            this.item.investment = this.investment;
                            this.$router.go(-1);
                        })
                        .catch((error) => {
                            if (error.response) {
                                if (error.response.status == 403) {
                                    this.$store.commit('status', 'close');
                                }
                            }
                        });
                }
            },
            event() {
                this.$http.post('items/'+this.item.id+'/event')
                    .then((response) => {
                        this.init = false;
                    });
            }
        },
        beforeMount: function () {
            if (this.item.investment == 0) {
                this.investment = 1;
            } else {
                this.investment = this.item.investment;
            }
        }
    }
</script>

<style>
    .slide-enter-active {
        transition: all .2s ease;
    }
    .slide-leave-active {
        display: none;
    }
    .slide-enter, .slide-leave-to
        /* .slide-fade-leave-active below version 2.1.8 */ {
        transform: translateX(35px);
        opacity: 0;
    }

    .navbar-header {
        font-size: 18px;
        line-height: 20px;
        color: white;
    }
    .navbar-header .left {
        float: left;
        padding: 14px 5px 14px 10px;
        font-size: 20px;
    }
    .navbar-header .center {
        padding: 15px 0;
        margin: 0 35px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        text-align: center;
    }
</style>
