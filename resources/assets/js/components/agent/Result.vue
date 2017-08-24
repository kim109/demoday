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
                <th class="text-center hidden-xs" @click="sortBy('normal')">
                    J-Coin 일반
                    <span v-if="order.normal == 1" class="glyphicon glyphicon-sort-by-attributes-alt" aria-hidden="true"></span>
                    <span v-if="order.normal == -1" class="glyphicon glyphicon-sort-by-attributes" aria-hidden="true"></span>
                </th>
                <th class="text-center hidden-xs" @click="sortBy('expert')">
                    J-Coin 전문가
                    <span v-if="order.expert == 1" class="glyphicon glyphicon-sort-by-attributes-alt" aria-hidden="true"></span>
                    <span v-if="order.expert == -1" class="glyphicon glyphicon-sort-by-attributes" aria-hidden="true"></span>
                </th>
                <th class="text-center" @click="sortBy('investment')">
                    실제 투자액
                    <span v-if="order.investment == 1" class="glyphicon glyphicon-sort-by-attributes-alt" aria-hidden="true"></span>
                    <span v-if="order.investment == -1" class="glyphicon glyphicon-sort-by-attributes" aria-hidden="true"></span>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(result, index) in results" :key="result.id">
                <td class="text-center hidden-xs hidden-sm">{{ index+1 }}</td>
                <td>{{ result.title }}</td>
                <td class="text-right hidden-xs">{{ result.normal.toLocaleString() }}</td>
                <td class="text-right hidden-xs">{{ result.expert.toLocaleString() }}</td>
                <td class="text-right">
                    {{ result.investment.toLocaleString('ko-KR', { style: 'currency', currency: 'KRW' }) }}
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th class="hidden-xs hidden-sm"></th>
                <th>총 투자금액</th>
                <th class="text-right hidden-xs">{{ total.normal.toLocaleString() }}</th>
                <th class="text-right hidden-xs">{{ total.expert.toLocaleString() }}</th>
                <th class="text-right">
                    {{ total.investment.toLocaleString('ko-KR', { style: 'currency', currency: 'KRW' }) }}
                </th>
            </tr>
        </tfoot>
    </table>
</template>

<script>
    export default {
        data: function() {
            return {
                results: null,
                order: {
                    id: null,
                    title: null,
                    normal: null,
                    expert: null,
                    investment: null
                }
            }
        },
        computed: {
            total: function() {
                var normal = 0;
                var expert = 0;
                var investment = 0;

                if (Array.isArray(this.results)) {
                    this.results.forEach(function(result) {
                        normal += result.normal;
                        expert += result.expert;
                        investment += result.investment;
                    });
                }

                return {"normal":normal, "expert":expert, "investment":investment};
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
        beforeMount: function () {
            this.$http.get('results').then((response) => {
                this.results = response.data
            });
        }
    }
</script>