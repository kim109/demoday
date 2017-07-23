import ons from 'onsenui';
import Vue from 'vue';
import VueOnsen from 'vue-onsenui';

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

Vue.use(VueOnsen);

var selected = null;

const list = {
    template: '#list',
    methods: {
        view(item) {
            selected = item;
            this.pageStack.push(view);
        }
    },
    data: function() {
        return {
            coin: null,
            items : null
        }
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
        axios.get('main/items')
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
            item : selected
        }
    },
    methods: {
        minus() {
            if(this.investment <= 1) {
                ons.notification.alert('최소 투자금은 1 입니다.');
            } else {
               this.investment--;
            }
        },
        plus() {
            if(this.investment >= 99) {
                ons.notification.alert('최대 투자금은 99 입니다.');
            } else {
               this.investment++;
            }
        },
        save() {
            let result = ons.notification.confirm({
                title: '투자금 설정',
                message: '투자금을 '+this.investment+'으로 설정하시겠습니까?',
                callback: (index) => {
                    if (index == 1) {
                        axios.post('main/investment', {'item':this.item.id, 'investment': this.investment})
                        .then((response) => {
                            this.item.investment = this.investment;
                            ons.notification.toast({
                                message: '투자금이 설정되었습니다.',
                                timeout: 1500
                            });
                        })
                        .catch((error) => {
                            if (error.response) {
                                ons.notification.alert('aaa');
                                console.log(error.response.data);
                            } else if (error.request) {
                                console.log(error.request);
                            } else {
                                console.log('Error', error.message);
                            }
                        });
                    }
                }
            });

        }
    },
    beforeMount: function () {
        this.investment = selected.investment;
        // console.log('##');
    },
    props: ['pageStack']
};

var vm = new Vue({
    el: '#app',
    template: '#main',
    data() {
        return {
            pageStack: [list],
        };
    },
    methods: {
        test(e) {
            // console.dir(e.currentPage);
        }
    }
});