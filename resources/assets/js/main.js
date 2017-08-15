import Vue from 'vue'
import ons from 'onsenui'
import VueOnsen from 'vue-onsenui'
import axios from 'axios'

// CSRT 토큰 설정
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

Vue.use(VueOnsen);

const store = {
    coin: null,
    items : null,
    selected: null
}

const list = {
    template: '#list',
    methods: {
        view(item) {
            store.selected = item;
            this.pageStack.push(view);
        }
    },
    data: function() {
        return store;
    },
    computed: {
        balance: function() {
            let consume = 0;
            if (this.items != null) {
                this.items.forEach((item) => {
                    consume += parseInt(item.investment);
                });
            }
            return this.coin-consume;
        }
    },
    beforeMount: function () {
        axios.get('items')
            .then((response) => {
                this.coin = response.data.coin;
                this.items = response.data.items;
            })
            .catch((error) => {
                console.log(error);
            });
    },
    props: ['pageStack']
};

const view = {
    template: '#view',
    data: function() {
        return {
            investment: 0,
            eventEnable: true,
            item: store.selected
        }
    },
    watch: {
        investment: function (newValue) {
            if (newValue < 1) {
                ons.notification.alert('최소 투자금은 1 입니다.');
                this.investment = 1;
            } else if (newValue > 91) {
                ons.notification.alert('최소 투자금은 91 입니다.');
                this.investment = 91;
            } else if (this.balance < 0) {
                let max = newValue + this.balance;
                ons.notification.alert('J-Coin 잔여금이 모두 소진 되었습니다.<br>다른 PT 투자금을 하향 조정해주세요.');
                this.investment = max;
            }
        }
    },
    computed: {
        balance: function() {
            let consume = 0;
            store.items.forEach((item) => {
                if (item != this.item) {
                    consume += parseInt(item.investment);
                }
            });
            return store.coin-consume-this.investment;
        },
        max: function() {
            let consume = 0;
            store.items.forEach((item) => {
                if (item != this.item) {
                    consume += parseInt(item.investment);
                }
            });
            return (store.coin-consume > 91) ? 91 : store.coin-consume;
        }
    },
    methods: {
        save() {
            let result = ons.notification.confirm({
                title: '투자금 설정',
                message: '투자금을 '+this.investment+'으로 설정하시겠습니까?',
                callback: (index) => {
                    if (index == 1) {
                        axios.post('items/'+this.item.id+'/investment', {'investment': this.investment})
                        .then((response) => {
                            store.selected.investment = this.investment;
                            ons.notification.toast({
                                message: '투자금이 설정되었습니다.',
                                timeout: 1500
                            });
                        })
                        .catch((error) => {
                            if (error.response) {
                                if (error.response.data.errors) {
                                    ons.notification.alert(error.response.data.errors.join('<br>'));
                                } else {
                                    console.log(error.response.data);
                                }
                            } else if (error.request) {
                                console.log(error.request);
                            } else {
                                console.log('Error', error.message);
                            }
                        });
                    }
                }
            });
        },
        event() {
            axios.post('items/'+this.item.id+'/event')
                .then((response) => {
                    this.eventEnable = false;
                });
        }
    },
    beforeMount: function () {
        this.investment = store.selected.investment;
    },
    props: ['pageStack']
};

var vm = new Vue({
    el: '#app',
    data: {
        pageStack: [list],
    }
});
