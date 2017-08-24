<template>
    <transition name="slide">
        <table class="table table-striped fixed">
            <thead>
                <tr>
                    <th @click="sortBy('title')">
                        PT
                        <span v-if="order.title == 1" class="glyphicon glyphicon-sort-by-attributes-alt" aria-hidden="true"></span>
                        <span v-if="order.title == -1" class="glyphicon glyphicon-sort-by-attributes" aria-hidden="true"></span>
                    </th>
                    <th class="text-center" style="width:4em;">일반</th>
                    <th class="text-center" style="width:4em;">전문가</th>
                    <th class="text-center" style="width:8em;" @click="sortBy('investment')">
                        실제 투자액
                        <span v-if="order.investment == 1" class="glyphicon glyphicon-sort-by-attributes-alt" aria-hidden="true"></span>
                        <span v-if="order.investment == -1" class="glyphicon glyphicon-sort-by-attributes" aria-hidden="true"></span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(result, index) in results" :key="result.id">
                    <td class="title">{{ result.title }}</td>
                    <td class="text-right">{{ result.normal.toLocaleString() }}</td>
                    <td class="text-right">{{ result.expert.toLocaleString() }}</td>
                    <td class="text-right">
                        {{ result.investment.toLocaleString('ko-KR', { style: 'currency', currency: 'KRW' }) }}
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th>총 투자금액</th>
                    <th class="text-right">{{ total.normal.toLocaleString() }}</th>
                    <th class="text-right">{{ total.expert.toLocaleString() }}</th>
                    <th class="text-right">
                        {{ total.investment.toLocaleString('ko-KR', { style: 'currency', currency: 'KRW' }) }}
                    </th>
                </tr>
            </tfoot>
        </table>
    </transition>
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

<style>
    table.fixed {
        width: 100%;
        table-layout: fixed;
    }

    td.title {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
