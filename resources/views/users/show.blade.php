@extends('app')

@section('title', $user->name)

@section('content')
@section('breadcrumbs', Breadcrumbs::render('user', $user))
  <div class="container">
    @include('users.user')
    @auth
      @if( !($user->isBlockedBy(Auth::user()) ||  Auth::user()->isBlockedBy($user)))
        <article-inifinite
          page-type='user'
        :authorized-id='@json(Auth::id())'
        :authorized-check='@json(Auth::check())'
        :user='@json($user->name)'
        >
        </article-inifinite>
      @endif
    @endauth
    @guest
      <article-inifinite
        page-type='user'
      :authorized-id='@json(Auth::id())'
      :authorized-check='@json(Auth::check())'
      :user='@json($user->name)'
      >
      </article-inifinite>
    @endguest
  </div>
@endsection
