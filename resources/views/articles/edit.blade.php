@extends('app')

@section('title', '投稿更新')


@section('content')
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="mt-3">
          <div class="pt-0">
            @include('error_card_list')
            <div class="card-text">
              <form method="POST" action="{{ route('articles.update', ['article' => $article]) }}">
                @method('PATCH')
                @include('articles.form')
                <button type="submit" class="btn blue-gradient btn-block">更新する</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
