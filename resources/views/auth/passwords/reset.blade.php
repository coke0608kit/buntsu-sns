@extends('app')

@section('title', 'パスワード再設定')

@section('content')
  <div class="container">
    <div class="text-center">
      <h2 class="h5 card-title text-center mt-4">- 新しいパスワードを設定 -</h2>
      <div class="mt-2 mb-3">
        <img style="width: 100px;margin-left: 27px;" src="{{ asset('images/BUNTSU_sikaku.svg')}}">
      </div>

      @include('error_card_list')

      <div class="card-text">
        <form method="POST" action="{{ route('password.update') }}">
          @csrf

          <input type="hidden" name="email" value="{{ $email }}">
          <input type="hidden" name="token" value="{{ $token }}">

          <div class="md-form">
            <label for="password">新しいパスワード</label>
            <input class="form-control" type="password" id="password" name="password" required>
          </div>

          <div class="md-form">
            <label for="password_confirmation">新しいパスワード(再入力)</label>
            <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" required>
          </div>

          <button class="btn btn-block border border-dark mt-5 mb-2 w-75" style="margin: 0 auto;" type="submit">送信</button>

        </form>
      </div>
    </div>
  </div>
@endsection
