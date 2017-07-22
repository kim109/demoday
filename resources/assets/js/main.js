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

const list = {
    template: '#list',
    methods: {
        view() {
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
        // `this` 는 vm 인스턴스를 가리킵니다.
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
    props: ['pageStack', 'item']
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