@extends('app')

@section('title', $user->name . 'のフォロワー')

@section('content')
@section('breadcrumbs', Breadcrumbs::render('followers', $user))
  <div class="container">
    @include('users.user')
    @foreach($followers as $person)
      @if($loop->first)
        <div class="mt-5 mb-10">
          <p>フォロワーのリスト</p>
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
