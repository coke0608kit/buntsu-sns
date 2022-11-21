@extends('app')

@section('title', '投稿検索')

@section('content')
@section('breadcrumbs', Breadcrumbs::render('searchArticle'))
  <div class="container mb-5">
    <ul class="nav nav-tabs nav-justified mt-3">
      <li class="nav-item">
        <a class="nav-link text-muted active"
           href="{{ route('search.article') }}">
          投稿検索
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-muted"
         href="{{ route('search.user') }}">
        ユーザ検索
        </a>
      </li>
    </ul>
    <search-article-tags-input
      :autocomplete-items='@json($allTagNames ?? [])'
      :random-items='@json($randomTagNames ?? [])'
      :authorized-id='@json(Auth::id())'
      :authorized-check='@json(Auth::check())'
    >
    </search-article-tags-input>
  </div>
@endsection