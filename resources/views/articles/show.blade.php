@extends('app')

@section('title', '投稿詳細')

@section('content')
@section('breadcrumbs', Breadcrumbs::render('post', $article))
  <div class="container">
    @include('articles.card')
  </div>
@endsection
