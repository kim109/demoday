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
      <component :is="page" v-for="page in pageStack" :page-stack="pageStack"></component>
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
        <v-ons-list-header>Default @{{ coin }}</v-ons-list-header>
        <v-ons-list-item v-for="item in items" modifier="chevron" @click="view" tappable>
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

  <template id="view" :item="item">
    <v-ons-page>
      <v-ons-toolbar>
        <div class="left">
          <v-ons-back-button>리스트</v-ons-back-button>
        </div>
        <div class="center">123</div>
      </v-ons-toolbar>
      <p style="text-align: center">This is the second page</p>
    </v-ons-page>
  </template>

  <div id="app"></div>
  <script src="{{ mix('/js/main.js') }}"></script>
</body>
</html>