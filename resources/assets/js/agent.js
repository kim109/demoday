require('es6-promise').polyfill()

import Vue from 'vue'
import VueRouter from 'vue-router'
import Vuex from 'vuex'
import axios from 'axios'
import { sync } from 'vuex-router-sync'
import Echo from 'laravel-echo'

Vue.use(VueRouter)
Vue.use(Vuex)

// CSRT 토큰 설정
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}
// Ajax Error 처리
axios.interceptors.response.use(null, function(error) {
    if (error.response && error.response.data.errors) {
        if (Array.isArray(error.response.data.errors)) {
            alert(error.response.data.errors.join("\n"));
        } else {
            alert(error.response.data.errors);
        }
    } else {
        console.log(error);
    }
    return Promise.reject(error);
});

window.Pusher = require('pusher-js');
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'e9fea37b5e6836c20abf',
    cluster: 'ap1',
    encrypted: true
});

Vue.prototype.$http = axios;

Vue.component('list', require('./components/agent/List.vue'))
Vue.component('item', require('./components/agent/Item.vue'))
Vue.component('result', require('./components/agent/Result.vue'))

const router = new VueRouter({
    // mode: 'history',
    routes: [
        { path: '/list', name: 'list', component: Vue.component('list') },
        { path: '/item/:index(\\d+)', name: 'item', component: Vue.component('item') },
        { path: '/result', name: 'result', component: Vue.component('result') }
    ]
})

const store = new Vuex.Store({
    state: {
        user: null,
        status: null,
        coin: 0,
        items: null
    },
    getters: {
        balance: state => {
            let consume = 0;
            if (Array.isArray(state.items)) {
                state.items.forEach((item) => {
                    consume += parseInt(item.investment);
                });
            }
            return state.coin-consume;
        }
    },
    mutations: {
        init (state, payload) {
            state.user = payload.user;
            state.status = payload.status;
            state.coin = payload.coin;
            state.items = payload.items;
        },
        status (state, payload) {
            state.status = payload;
        }
    }
})

sync(store, router);

const app = new Vue({
    router,
    store,
    data: {
        title: 'Together JiranFamily DemoDay'
    },
    watch: {
        '$route' (to, from) {
            let last = to.path.split('/').pop();

            if (/^\d+$/.test(last)) {
                last = parseInt(last);
                this.title = this.$store.state.items[last].title;
            } else {
                this.title = 'Together JiranFamily DemoDay'
            }
        }
    },
    methods: {
        logout: function (event) {
            event.preventDefault();

            if (window.confirm('로그아웃 하시겠습니까?')) {
                document.getElementById('logout').submit();
            }
        }
    },
    beforeMount: function () {
        axios.get('items').then((response) => {
            store.commit('init', response.data);
        });

        this.$router.replace('/list');
    },
    mounted: function () {
        let self = this;
        window.Echo.channel('demoday')
            .listen('.winner', function (e) {
                if (e.id == self.$store.state.user.username) {
                    alert(e.name+'님이 당첨 되었습니다!');
                }
            })
            .listen('.closed', function (e) {
                self.$router.push('/result');
            });
    }
}).$mount('#app')
