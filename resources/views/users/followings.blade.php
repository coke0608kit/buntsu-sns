@extends('app')

@section('title', $user->name . 'のフォロー中')

@section('content')
@section('breadcrumbs', Breadcrumbs::render('followings', $user))
  <div class="container">
    @include('users.user')
    @foreach($followings as $person)
      @if($loop->first)
        <div class="mt-5 mb-10">
          <p>フォローしたリスト</p>
          <ul class="list-group">
      @endif
      @include('users.person')
      @if($loop->last)
          </ul>
        </div>
      @endif
    @endforeach
  </div>
@endsection


