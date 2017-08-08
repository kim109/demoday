<template>
    <div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="text-center hidden-xs hidden-sm" @click="sortBy('id')">
                        No.
                        <i v-if="order.id == 1" class="fa fa-sort-amount-desc" aria-hidden="true"></i>
                        <i v-if="order.id == -1" class="fa fa-sort-amount-asc" aria-hidden="true"></i>
                    </th>
                    <th @click="sortBy('name')">
                        이름
                        <i v-if="order.title == 1" class="fa fa-sort-amount-desc" aria-hidden="true"></i>
                        <i v-if="order.title == -1" class="fa fa-sort-amount-asc" aria-hidden="true"></i>
                    </th>
                    <th class="hidden-xs" @click="sortBy('username')">
                        ID
                        <i v-if="order.coin == 1" class="fa fa-sort-amount-desc" aria-hidden="true"></i>
                        <i v-if="order.coin == -1" class="fa fa-sort-amount-asc" aria-hidden="true"></i>
                    </th>
                    <th class="text-center" @click="sortBy('investment')">
                        J-Coin 투자액
                        <i v-if="order.investment == 1" class="fa fa-sort-amount-desc" aria-hidden="true"></i>
                        <i v-if="order.investment == -1" class="fa fa-sort-amount-asc" aria-hidden="true"></i>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(value, key, index) in data">
                    <td class="hidden-xs hidden-sm">{{ key+1 }}</td>
                    <td>{{ value.name }}</td>
                    <td>{{ value.username }}</td>
                    <td class="text-right">
                        {{ value.investment.toLocaleString() }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: {
            id: {
                required: true
            }
        },
        data: function() {
            return {
                data: null,
                total: null,
                order: {
                    name: null,
                    username: null,
                    investment: null
                }
            }
        },
        methods: {
            sortBy: function(column) {
                for (var i in this.order) {
                    if (i == column && this.order[i] == null) {
                        this.order[i] = 1
                    } else if (i != column) {
                        this.order[i] = null;
                    }
                }

                this.results.sort((a, b) => {
                    a = a[column];
                    b = b[column];
                    return (a === b ? 0 : a > b ? 1 : -1) * this.order[column];
                });

                this.order[column] = this.order[column] * -1;
            }
        },
        mounted: function () {
            axios.get('admin/results/'+this.id)
            .then((response) => {
                this.total = response.data.total;
                this.data = response.data.data;
            })
            .catch((error) => {
                console.log(error);
            });
        }
    }
</script>
