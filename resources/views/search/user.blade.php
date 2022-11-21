@extends('app')

@section('title', 'ユーザ検索')

@section('content')
@section('breadcrumbs', Breadcrumbs::render('searchUser'))
  <div class="container mb-5">
    <ul class="nav nav-tabs nav-justified mt-3">
      <li class="nav-item">
        <a class="nav-link text-muted "
           href="{{ route('search.article') }}">
          投稿検索
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-muted active"
         href="{{ route('search.user') }}">
        ユーザ検索
        </a>
      </li>
    </ul>
    <search-user-tags-input
      :autocomplete-items='@json($allHobbyNames ?? [])'
      :authorized-id='@json(Auth::id())'
      :authorized-check='@json(Auth::check())'
      :all-users-count='@json($allUsersCount ?? 0)'
    >
    </search-user-tags-input>
  </div>
@endsection