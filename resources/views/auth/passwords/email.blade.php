@extends('app')

@section('title', 'パスワード再設定')

@section('content')
  <div class="container">
    <div class="text-center">
      <h2 class="h5 card-title text-center mt-4">- パスワード再設定 -</h2>
      <div class="mt-2 mb-3">
        <img style="width: 100px;margin-left: 27px;" src="{{ asset('images/BUNTSU_sikaku.svg')}}">
      </div>

      @include('error_card_list')

      @if (session('status'))
        <div class="card-text alert alert-success">
          {{ session('status') }}
        </div>
      @endif

      <div class="card-text">
        <form method="POST" action="{{ route('password.email') }}">
          @csrf
          <div class="md-form">
            <label for="email">メールアドレス</label>
            <input class="form-control" type="text" id="email" name="email" required>
          </div>

          <button class="btn btn-block border border-dark mt-5 mb-2 w-75" style="margin: 0 auto;" type="submit">メール送信</button>
        </form>
      </div>
    </div>
  </div>
@endsection
