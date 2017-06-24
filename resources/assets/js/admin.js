window.$ = window.jQuery = require('jquery');
require('bootstrap');

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

window.Vue = require('vue');

Vue.component('item', require('./components/Item.vue'));

require('bootstrap-switch');
$.fn.bootstrapSwitch.defaults.size = 'small';

$("[name='my-checkbox']").bootstrapSwitch();

var setting = new Vue({
    el: '#admin',
    data: {
        supply: null,
        capital: null,
        item: {
            title: null,
            company: null,
            speaker: null,
            description: null
        }
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
        },
        storeItem: function (event) {
            let self = this;
            axios.post('admin/items', self.item)
            .then((response) => {
                self.item.title = null;
                self.item.company = null;
                self.item.speaker = null;
                self.item.description = null;
            })
            .catch((error) => {
                console.log(error);
            });
        }
    }
});