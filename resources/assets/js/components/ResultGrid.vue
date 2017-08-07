<template>
    <table class="table table-striped">
        <thead>
            <tr>
                <th class="text-center hidden-xs hidden-sm" @click="sortBy('id')">
                    No.
                    <i v-if="order.id == 1" class="fa fa-sort-amount-desc" aria-hidden="true"></i>
                    <i v-if="order.id == -1" class="fa fa-sort-amount-asc" aria-hidden="true"></i>
                </th>
                <th class="text-center" @click="sortBy('title')">
                    PT
                    <i v-if="order.title == 1" class="fa fa-sort-amount-desc" aria-hidden="true"></i>
                    <i v-if="order.title == -1" class="fa fa-sort-amount-asc" aria-hidden="true"></i>
                </th>
                <th class="text-center hidden-xs" @click="sortBy('coin')">
                    J-Coin 총 투자액
                    <i v-if="order.coin == 1" class="fa fa-sort-amount-desc" aria-hidden="true"></i>
                    <i v-if="order.coin == -1" class="fa fa-sort-amount-asc" aria-hidden="true"></i>
                </th>
                <th class="text-center" @click="sortBy('investment')">
                    실제 투자액
                    <i v-if="order.investment == 1" class="fa fa-sort-amount-desc" aria-hidden="true"></i>
                    <i v-if="order.investment == -1" class="fa fa-sort-amount-asc" aria-hidden="true"></i>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="result in results">
                <td class="hidden-xs hidden-sm">{{ result.id }}</td>
                <td>{{ result.title }}</td>
                <td class="text-right hidden-xs">{{ result.coin.toLocaleString() }}</td>
                <td class="text-right">
                    <i class="fa fa-krw" aria-hidden="true"></i> {{ result.investment.toLocaleString() }}
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        props: {
            results: {
                required: true
            }
        },
        data: function() {
            return {
                order: {
                    id: null,
                    title: null,
                    coin: null,
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
        }
    }
</script>
