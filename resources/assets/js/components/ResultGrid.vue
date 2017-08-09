<template>
    <table class="table table-striped">
        <thead>
            <tr>
                <th class="text-center hidden-xs hidden-sm" @click="sortBy('id')">
                    No.
                    <span v-if="order.id == 1" class="glyphicon glyphicon-sort-by-attributes-alt" aria-hidden="true"></span>
                    <span v-if="order.id == -1" class="glyphicon glyphicon-sort-by-attributes" aria-hidden="true"></span>
                </th>
                <th class="text-center" @click="sortBy('title')">
                    PT
                    <span v-if="order.title == 1" class="glyphicon glyphicon-sort-by-attributes-alt" aria-hidden="true"></span>
                    <span v-if="order.title == -1" class="glyphicon glyphicon-sort-by-attributes" aria-hidden="true"></span>
                </th>
                <th class="text-center hidden-xs" @click="sortBy('coin')">
                    J-Coin 총 투자액
                    <span v-if="order.coin == 1" class="glyphicon glyphicon-sort-by-attributes-alt" aria-hidden="true"></span>
                    <span v-if="order.coin == -1" class="glyphicon glyphicon-sort-by-attributes" aria-hidden="true"></span>
                </th>
                <th class="text-center" @click="sortBy('investment')">
                    실제 투자액
                    <span v-if="order.investment == 1" class="glyphicon glyphicon-sort-by-attributes-alt" aria-hidden="true"></span>
                    <span v-if="order.investment == -1" class="glyphicon glyphicon-sort-by-attributes" aria-hidden="true"></span>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(result, index) in results">
                <td class="text-center hidden-xs hidden-sm">{{ index+1 }}</td>
                <td><a href="#" @click="detail($event, result)">{{ result.title }}</a></td>
                <td class="text-right hidden-xs">{{ result.coin.toLocaleString() }}</td>
                <td class="text-right">
                    {{ result.investment.toLocaleString('ko-KR', { style: 'currency', currency: 'KRW' }) }}
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th class="hidden-xs hidden-sm"></th>
                <th>총 투자금액</th>
                <th class="text-right hidden-xs">{{ total.coin.toLocaleString() }}</th>
                <th class="text-right">
                    {{ total.investment.toLocaleString('ko-KR', { style: 'currency', currency: 'KRW' }) }}
                </th>
            </tr>
        </tfoot>
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
        computed: {
            total: function() {
                var coin = 0;
                var investment = 0;

                this.results.forEach(function(result) {
                    coin += result.coin;
                    investment += result.investment;
                });

                return {"coin":coin, "investment":investment};
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
            },
            detail: function(event, result) {
                event.preventDefault();
                this.$emit('detail', result);
            }
        }
    }
</script>
