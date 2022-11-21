@extends('app')

@section('title', 'ログイン')

@section('content')
  <div class="container">
    <div class="my-3">
      <div class="text-center">
        <div class="mb-3">
          <img style="width: 100px;margin-left: 27px;" src="{{ asset('images/BUNTSU_sikaku.svg')}}">
        </div>

        @include('error_card_list')

        <div class="card-text">
          <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="md-form">
              <label for="email">メールアドレス</label>
              <input class="form-control" type="text" id="email" name="email" required>
            </div>

            <div class="md-form">
              <label for="password">パスワード</label>
              <input class="form-control" type="password" id="password" name="password" required>
            </div>

            <input type="hidden" name="remember" id="remember" value="on">

            <div class="text-left">
              <a href="{{ route('password.request') }}" class="card-text">パスワードを忘れた方</a>
            </div>

            <button class="border border-dark btn btn-block mt-5 mb-2 w-75" style="margin: 0 auto;" type="submit">ログイン</button>

          </form>

          <div class="mt-0">
            <a href="{{ route('register') }}" class="card-text">ユーザー登録はこちら</a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection