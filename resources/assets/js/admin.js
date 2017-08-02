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

var setting = new Vue({
    el: '#admin',
    data: {
        supply: null,
        capital: null,
        experts: null,
        multiple: null,
        state: null,
        items: null,
        item: {
            title: null,
            company: null,
            speaker: null,
            description: null
        }
    },
    computed: {
        notReady: function () {
            return this.state != 'ready';
        }
    },
    created: function () {
        axios.get('admin/setting')
            .then((response) => {
                this.supply = response.data.supply;
                this.capital = response.data.capital;
                this.experts = response.data.experts;
                this.multiple = response.data.multiple;
                this.state = response.data.state;
                this.items = response.data.items;
            })
            .catch((error) => {
                console.log(error);
            });
    },
    methods: {
        saveSetting: function (type) {
            let param = {};
            param[type] = this[type];

            axios.patch('admin/setting', param)
            .catch((error) => {
                console.log(error);
            });
        },
        saveState: function (event) {
            let value = document.getElementById("state").value;
            let self = this;

            axios.patch('admin/setting', {'state': value})
                .then((response) => {
                    self.state = value;
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

                self.items.push(response.data.item);
            })
            .catch((error) => {
                console.log(error);
            });
        },
        removeItem: function (index) {
            this.items.splice(index, 1);
        }
    }
});