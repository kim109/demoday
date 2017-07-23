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
                alert('최소 투자금은 1 입니다.');
            } else {
               this.investment--;
            }
        },
        plus() {
            if(this.investment >= 99) {
                alert('최대 투자금은 99 입니다.');
            } else {
               this.investment++;
            }
        },
        save() {
            axios.post('main/investment', {'item':this.item.id, 'investment': this.investment})
                .then((response) => {
                    this.item.investment = this.investment;
                })
                .catch((error) => {
                    console.log(error);
                });
        }
    },
    beforeMount: function () {
        this.investment = selected.investment;
        console.log('##');
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
            console.dir(e.currentPage);
        }
    }
});