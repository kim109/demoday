window.$ = require('jquery');
require('bootstrap-switch');
$.fn.bootstrapSwitch.defaults.size = 'small';

window.Vue = require('vue');

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

var setting = new Vue({
    el: '#setting',
    data: {
        supply: null,
        capital: null
    },
    created: function () {
        axios.get('admin/setting')
            .then((response) => {
                this.supply = response.data.supply;
                this.capital = response.data.capital;
            })
            .catch((error) => {
                console.log(error);
            });
        $("[name='my-checkbox']").bootstrapSwitch();
    },
    methods: {
        saveSupply: function (event) {
            let self = this;
            axios.patch('admin/setting', {
                'supply': self.supply
            })
            .catch((error) => {
                console.log(error);
            });
        },
        saveCapital: function (event) {
            let self = this;
            axios.patch('admin/setting', {
                'capital': self.capital
            })
            .catch((error) => {
                console.log(error);
            });
        }
    }
});