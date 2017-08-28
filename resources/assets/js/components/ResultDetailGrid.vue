<template>
    <div>
        <ol class="breadcrumb">
            <li><a href="#" @click="overview">전체</a></li>
            <li class="active">
                {{ result.title }}
                <small>( 일반 : {{ result.normal.toLocaleString() }} / 전문가 : {{ result.expert.toLocaleString() }}
                    <span class="hidden-xs">/ {{ result.investment.toLocaleString('ko-KR', { style: 'currency', currency: 'KRW'}) }}</span> )
                </small>
            </li>
        </ol>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="text-center hidden-xs hidden-sm">No.</th>
                    <th @click="sortBy('name')">
                        이름
                        <span v-if="order.name == 1" class="glyphicon glyphicon-sort-by-attributes-alt" aria-hidden="true"></span>
                        <span v-if="order.name == -1" class="glyphicon glyphicon-sort-by-attributes" aria-hidden="true"></span>
                    </th>
                    <th @click="sortBy('username')">
                        ID
                        <span v-if="order.username == 1" class="glyphicon glyphicon-sort-by-attributes-alt" aria-hidden="true"></span>
                        <span v-if="order.username == -1" class="glyphicon glyphicon-sort-by-attributes" aria-hidden="true"></span>
                    </th>
                    <th class="text-center" @click="sortBy('investment')">
                        J-Coin 투자액
                        <span v-if="order.investment == 1" class="glyphicon glyphicon-sort-by-attributes-alt" aria-hidden="true"></span>
                        <span v-if="order.investment == -1" class="glyphicon glyphicon-sort-by-attributes" aria-hidden="true"></span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(value, index) in sliceData" :key="value.username">
                    <td class="text-center hidden-xs hidden-sm">{{ rows * (page-1) + index + 1 }}</td>
                    <td>{{ value.name }}</td>
                    <td>{{ value.username }}</td>
                    <td class="text-right">
                        {{ value.investment }}
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="text-center" v-if="totalPage != 1">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li v-for="n in totalPage" :class="{ active: n == page }">
                        <a href="#" @click.prevent="page = n">{{ n }}</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            result: {
                required: true
            }
        },
        data: function() {
            return {
                data: [],
                page: 1,
                rows: 15,
                order: {
                    name: null,
                    username: null,
                    investment: -1
                }
            }
        },
        computed: {
            totalPage: function() {
                return Math.ceil(this.data.length / this.rows);
            },
            sliceData: function() {
                let start = (this.page-1) * this.rows;
                let end = start+this.rows > this.data.length ? this.data.length : start+this.rows;
                
                return this.data.slice(start, end);
            },
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

                this.data.sort((a, b) => {
                    a = a[column];
                    b = b[column];
                    return (a === b ? 0 : a > b ? 1 : -1) * this.order[column];
                });

                this.order[column] = this.order[column] * -1;
            },
            overview: function(event) {
                event.preventDefault();
                this.$emit('overview');
            }
        },
        mounted: function () {
            this.$http.get('admin/results/'+this.result.id)
                .then((response) => {
                    this.data = response.data;
                    this.sortBy('investment');
                });
        }
    }
</script>
