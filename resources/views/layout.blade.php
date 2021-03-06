<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

  <title>{{ config('app.name') }}</title>
  @stack('stylesheets')
</head>

<body>
  <div class="container">
    @yield('content')
  </div>
  @stack('scripts')
</body>
</html>
