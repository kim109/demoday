<template>
    <div>
        <ol class="breadcrumb">
            <li><a href="#" @click="overview">전체</a></li>
            <li class="active">
                {{ result.title }}
                <small>( {{ result.coin.toLocaleString() }} J-Coin
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
                <tr v-for="(value, index) in data" :key="value.username">
                    <td class="text-center hidden-xs hidden-sm">{{ index+1 }}</td>
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
            result: {
                required: true
            }
        },
        data: function() {
            return {
                data: null,
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
            axios.get('admin/results/'+this.result.id)
            .then((response) => {
                this.data = response.data;
            })
            .catch((error) => {
                console.log(error);
            });
        }
    }
</script>
