<template>
    <div>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="/main">Together JiranFamily DemoDay</a>
                </div>
            </div>
        </nav>

        <h4 class="text-center">
            지급된 Coin : {{ coin.toLocaleString() }} / 잔여코인 : <strong>{{ this.$store.getters.balance.toLocaleString() }}</strong>
        </h4>

        <div class="list-block">
            <ul class="list-unstyled">
                <li v-for="(item, index) in items" :key="index">
                    <div class="item">
                        <router-link :to="{ name: 'item', params: { index: index }}">
                            <div class="item-title"><strong>{{ item.title }}</strong></div>
                            <div class="item-subtitle small">투자금 : {{ item.investment }} ({{ (item.investment/coin*100).toFixed(2) }}%)</div>
                        </router-link>
                    </div>
                </li>
            </ul>
        </div>

        <div class="container">
            <button type="button" class="btn btn-primary btn-block">결과 확인</button>
        </div>
    </div>
</template>

<script>
    export default {
        computed: {
            items () {
                return this.$store.state.items;
            },
            coin () {
                return this.$store.state.coin;
            }
        }
    }
</script>

<style>
    .list-block ul {
        margin: 30px 0;
        border-bottom: 1px solid #cccccc;
    }
    .list-block ul li {
        border-top: 1px solid #cccccc;
        padding: 10px 0 10px 10px;
    }

    .list-block ul li .item {
        margin-right: 10px;
        padding-right: 15px;
        background: no-repeat right center;
        background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg%20viewBox%3D'0%200%2060%20120'%20xmlns%3D'http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg'%3E%3Cpath%20d%3D'm60%2061.5-38.25%2038.25-9.75-9.75%2029.25-28.5-29.25-28.5%209.75-9.75z'%20fill%3D'%23c7c7cc'%2F%3E%3C%2Fsvg%3E");
        background-size: 10px 20px;
    }

    .list-block ul li a {
        text-decoration: none;
        color: #333333;
    }

    .list-block ul li .item-title {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .list-block ul li .item-subtitle {
        color: #757575;
    }
</style>