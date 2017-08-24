window.$ = window.jQuery = require('jquery');
require('bootstrap');
require('es6-promise').polyfill();

import Vue from 'vue'
import vSelect from 'vue-select'
import axios from 'axios'

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

Vue.component('v-select', vSelect)
Vue.component('item', require('./components/Item.vue'))
Vue.component('result-grid', require('./components/ResultGrid.vue'))
Vue.component('result-detail-grid', require('./components/ResultDetailGrid.vue'))

var setting = new Vue({
    el: '#admin',
    data: {
        supply: null,
        capital: null,
        experts: null,
        ratio: null,
        status: null,
        items: null,
        item: {
            title: null,
            company: null,
            speaker: null,
            description: null
        },
        results: null,
        selectedResult: null,
        expertOptions: null
    },
    computed: {
        notReady: function () {
            return this.status != 'ready';
        }
    },
    created: function () {
        this.$http.get('admin/setting')
            .then((response) => {
                for (var key in response.data) {
                    this[key] = response.data[key];
                }
            });
    },
    methods: {
        reset: function (event) {
            event.preventDefault();

            if (window.confirm('리셋 하시겠습니까?')) {
                this.$http.delete('admin/reset')
                    .then((response) => {
                        this.$http.get('admin/setting')
                            .then((response) => {
                                for (var key in response.data) {
                                    this[key] = response.data[key];
                                }
                            });
                    });
                alert('리셋 되었습니다.');
            }
        },
        saveSetting: function (type) {
            if (window.confirm('저장 하시겠습니까?')) {
                let param = {};
                param[type] = this[type];

                this.$http.patch('admin/setting', param);
            }
        },
        saveStatus: function (event) {
            if (window.confirm('진행 상태를 변경 하시겠습니까?')) {
                let value = document.getElementById("status").value;
                let self = this;

                this.$http.patch('admin/setting', {'status': value})
                    .then((response) => {
                        self.status = value;
                    });
            } else {
                document.getElementById("status").value = this.status;
            }
        },
        getExperts: function(search, loading) {
            loading(true);
            this.$http.get('admin/experts/options?q='+search)
                .then((response) => {
                    this.expertOptions = response.data.items;
                    loading(false);
                });
        },
        storeItem: function (event) {
            if (window.confirm('신규  PT를 등록 하시겠습니까?')) {
                let self = this;
                this.$http.post('admin/items', self.item)
                    .then((response) => {
                        self.item.title = null;
                        self.item.company = null;
                        self.item.speaker = null;
                        self.item.description = null;

                        self.items.push(response.data.item);
                    });
            }
        },
        removeItem: function (index) {
            this.items.splice(index, 1);
        },
        showResult: function () {
            this.$http.get('admin/results')
                .then((response) => {
                    this.results = response.data;
                    $('#modal').modal('show');
                });
        },
        showResultDetail: function(item) {
            this.selectedResult = item;
        }
    }
});
