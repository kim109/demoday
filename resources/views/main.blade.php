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
        <v-ons-list-header>지급된 Coin : @{{ coin }}</v-ons-list-header>
        <v-ons-list-item v-for="(item, index) in items" v-bind:key="index" modifier="chevron" @click="view(item)" tappable>
          <span class="list-item__title">@{{ item.title }}</span>
          <span class="list-item__subtitle">투자금 : </span>
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
        <div class="content">제목</div>
        <div class="title">@{{ item.title }}</div>
        <div class="content">회사</div>
        <div class="title">@{{ item.company }}</div>
        <div class="content">발표자</div>
        <div class="title">@{{ item.speaker }}</div>
        <div class="content">내용 요약</div>
        <div class="title">@{{ item.description }}</div>
      </v-on-card>
      <v-ons-bottom-toolbar>
        <v-ons-button @click="test">
          Test!
        </v-ons-button>
      </v-ons-bottom-toolbar>
    </v-ons-page>
  </template>

  <div id="app"></div>
  <script src="{{ mix('/js/main.js') }}"></script>
</body>
</html>