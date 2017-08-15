<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ mix('/css/main.css') }}">
</head>

<body>
  <div id="app">
    <v-ons-navigator :page-stack="pageStack">
      <component :is="page" v-for="(page, index) in pageStack" :key="index" :page-stack="pageStack"></component>
    </v-ons-navigator>
  </div>

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
        <v-ons-list-item v-for="(item, index) in items" :key="index" modifier="chevron" @click="view(item)" tappable>
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
        <div class="title">
          <v-ons-row>
            <v-ons-col>J-Coin 투자금액</v-ons-col>
            <v-ons-col width="85px">
              <v-ons-button :disabled="!eventEnable" @click="event">출석 체크</v-ons-button>
            </v-ons-col>
          </v-ons-row>
        </div>
        <div class="content" style="text-align: center">
          <p style="text-align: left">잔여 코인 : @{{ balance }} / 투자금액 : @{{ investment }}</p>
          <v-ons-range style="width: 100%;" v-model.number="investment" min="1" :max="max"></v-ons-range>
          <v-ons-button modifier="large" style="margin: 6px 0" @click="save">저 장</v-ons-button>
        </div>
      </v-ons-card>
    </v-ons-page>
  </template>

  <script src="{{ mix('/js/main.js') }}"></script>
</body>
</html>
