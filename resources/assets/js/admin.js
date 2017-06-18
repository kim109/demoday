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
    },
    computed: {
        isDisabled() {
            // evaluate whatever you need to determine disabled here...
            return this.form.validated;
        }
    },
    methods: {
        saveSupply: function (event) {
            let self = this;
            axios.patch('admin/setting', {
                'supply': self.supply
            });
        },
        saveCapital: function (event) {
            console.log(this.capital);
        }
    }
});