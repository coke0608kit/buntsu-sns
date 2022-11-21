@extends('app')

@section('title', 'ユーザー登録')

@section('content')
  <div class="container">
    <div class="text-center">
      <h2 class="h5 card-title text-center mt-4">- 会員登録 -</h2>
      <div class="mt-2 mb-3">
        <img style="width: 100px;margin-left: 27px;" src="{{ asset('images/BUNTSU_sikaku.svg')}}">
      </div>

      @include('error_card_list')

      <div class="card-text">
        <form method="POST" action="{{ route('register') }}">
          @csrf
          <div class="md-form">
            <label for="name">ユーザー名</label>
            <input class="form-control" type="text" id="name" name="name" required value="{{ old('name') }}">
            <small>英数字3〜16文字(登録後の変更はできません)</small>
          </div>
          <div class="md-form">
            <label for="nickname">ニックネーム</label>
            <input class="form-control" type="text" id="nickname" name="nickname" required value="{{ old('nickname') }}">
            <small>3〜16文字(登録後に変更が可能です)</small>
          </div>
          <div class="md-form">
            <label for="email">メールアドレス</label>
            <input class="form-control" type="text" id="email" name="email" required value="{{ old('email') }}" >
          </div>
          <div class="md-form">
            <label for="password">パスワード(8文字以上)</label>
            <input class="form-control" type="password" id="password" name="password" required>
          </div>
          <div class="md-form">
            <label for="password_confirmation">パスワード(確認)</label>
            <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" required>
          </div>
          <div class="custom-control custom-checkbox my-4">
            <input type="checkbox" name="agreement" value="yes" class="custom-control-input" required id="id_agreement">
            <label class="custom-control-label" for="id_agreement">
              <a style="margin-left: 10px;" href="/terms/" target="_blank">利用規約 <i class="fas fa-external-link-alt" aria-hidden="true"></i></a> と <a href="/privacy/" target="_blank">プライバシーポリシー <i class="fas fa-external-link-alt" aria-hidden="true"></i></a> に同意する
            </label>
          </div>
          <button class="btn btn-block border border-dark mt-5 mb-2 w-75" style="margin: 0 auto;" type="submit">ユーザー登録</button>
        </form>
        
        <div class="mt-0">
          <a href="{{ route('login') }}" class="card-text">ログインはこちら</a>
        </div>
      </div>
    </div>
  </div>
@endsection
