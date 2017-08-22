import Vue from 'vue'
import VueRouter from 'vue-router'
import Vuex from 'vuex'
import axios from 'axios'

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

Vue.prototype.$http = axios;

Vue.component('list', require('./components/agent/List.vue'))
Vue.component('item', require('./components/agent/Item.vue'))

const router = new VueRouter({
    // mode: 'history',
    routes: [
        { path: '/list', name: 'list', component: Vue.component('list') },
        { path: '/item/:index(\\d+)', name: 'item', component: Vue.component('item') }
    ]
})

const store = new Vuex.Store({
    state: {
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
        state.status = payload.status;
        state.coin = payload.coin;
        state.items = payload.items;
      },
      status (state, payload) {
        state.status = payload;
      }
    }
})

const app = new Vue({
    router,
    store,
    beforeMount: function () {
        axios.get('items').then((response) => {
            store.commit('init', response.data);
        });

        this.$router.replace('/list');
    }
}).$mount('#app')
