@extends('layout')

@push('stylesheets')
    <meta name="theme-color" content="#E95420">
    <link rel="manifest" href="/manifest.json">

    <link rel="apple-touch-icon" href="/images/logo-144x144.png">
    <link rel="apple-touch-startup-image" href="/images/apple-touch-startup-image.png">
    <meta name="apple-mobile-web-app-title" content="DemoDay">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <link rel="stylesheet" href="{{ mix('/css/login.css') }}">
@endpush

@push('scripts')
@endpush

@section('content')
<div class="login">
    <div class="form-signin">
        <div class="text-center">
            <img src="images/jiran_ci.png" class="img-responsive ci" alt="Jiran Logo">
            <h4>T.J.D 모의 투자</h4>
        </div>
        <hr>
        <div class="tab-content">
            <div id="login" class="tab-pane active">
                <form method="post" action="{{ url('/login') }}">
                    {!! csrf_field() !!}
                    <p class="text-muted text-center">
                        쿨메신저 ID와 비밀번호를 입력해주세요.
                    </p>
@if (count($errors) > 0)
                    <!-- Form Error List -->
                    <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p>{!! nl2br(e($error)) !!}</p>
                    @endforeach
                    </div>
@endif
                    <input type="text" placeholder="UserID" name="username" class="form-control top" required autofocus>
                    <input type="password" placeholder="Password" name="password" class="form-control bottom" required>

                    <button class="btn btn-primary btn-block" type="submit">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
