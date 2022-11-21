@extends('app')

@section('title', '投稿一覧')

@section('content')
  <div class="container mb-5">
    <ul class="nav nav-tabs nav-justified mt-3">
      @auth
      <li class="nav-item">
        <a class="nav-link text-muted active"
           href="{{ route('articles.index') }}">
          HOME
        </a>
      </li>
      @endauth
      <li class="nav-item">
        <a class="nav-link text-muted"
        href="{{ route('articles.all') }}">
          みんなの投稿
        </a>
      </li>
    </ul>
    <article-inifinite
      page-type='index'
     :authorized-id='@json(Auth::id())'
     :authorized-check='@json(Auth::check())'
     >
    </article-inifinite>
  </div>
@endsection