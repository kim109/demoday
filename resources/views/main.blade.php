<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ mix('/css/main.css') }}">
</head>

<body>
  <template id="main">
    <v-ons-navigator :page-stack="pageStack" @prepush="test">
      <component :is="page" v-for="(page, index) in pageStack" v-bind:key="index" :page-stack="pageStack"></component>
    </v-ons-navigator>
  </template>

  <template id="list">
    <v-ons-page>
      <v-ons-toolbar>
        <div class="center">Together JiranFamily DemoDay</div>
        <div class="right">
          <v-ons-toolbar-button>
            <v-ons-icon icon="ion-navicon, material: md-menu"></v-ons-icon>
          </v-ons-toolbar-button>
        </div>
      </v-ons-toolbar>

      <v-ons-list>
        <v-ons-list-header>지급된 Coin : @{{ coin }} / 잔여 Coin: @{{ balance }}</v-ons-list-header>
        <v-ons-list-item v-for="(item, index) in items" v-bind:key="index" modifier="chevron" @click="view(item)" tappable>
          <span class="list-item__title">@{{ item.title }}</span>
          <span class="list-item__subtitle">투자금 : @{{ item.investment }} (@{{ (item.investment/coin*100).toFixed(2) }}%)</span>
        </v-ons-list-item>
      </v-ons-list>

      <p style="text-align: center">
        <v-ons-button @click="$ons.notification.alert('Hello World!')">
          Click me!
        </v-ons-button>
      </p>
    </v-ons-page>
  </template>

  <template id="view">
    <v-ons-page>
      <v-ons-toolbar>
        <div class="left">
          <v-ons-back-button>Back</v-ons-back-button>
        </div>
        <div class="center">@{{ item.title }}</div>
      </v-ons-toolbar>

      <v-ons-card>
        <div class="title">@{{ item.title }}</div>
        <div class="content">
          <p>회사 : @{{ item.company }}</p>
          <p>발표자 : @{{ item.speaker }}</p>
          <p>내용 요약</p>
          <p v-html="item.description"></p>
        </div>
      </v-ons-card>

      <v-ons-card>
        <div class="title">J-Coin 투자금액</div>
        <div class="content" style="text-align: center">
          <p style="text-align:right"><v-ons-button style="margin: 6px 0" @click="event">출석 체크</v-ons-button></p>
          <p style="text-align: left">잔여 코인 : @{{ balance }}</p>
          <button @click="investment -= 1"><v-ons-icon icon="ion-minus-round"></v-ons-icon></button>
          <input style="text-align: center;padding: 2px 0" type="number" id="investment" min="1" max="99" v-model.number.lazy="investment">
          <button @click="investment += 1"><v-ons-icon icon="ion-plus-round"></v-ons-icon></button>

          <v-ons-button modifier="large" style="margin: 6px 0" @click="save">저 장</v-ons-button>
        </div>
      </v-ons-card>
    </v-ons-page>
  </template>

  <div id="app"></div>
  <script src="{{ mix('/js/main.js') }}"></script>
</body>
</html>
