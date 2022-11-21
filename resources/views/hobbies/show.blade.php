@extends('app')

@section('title', '#' . $tagName)

@section('content')
@section('breadcrumbs', Breadcrumbs::render('hobby', $tagName))
  <div class="container">
    <div class="card mt-3">
      <div class="card-body">
        <h2 class="h4 card-title m-0">{{ '#' . $tagName }}</h2>
        <div class="card-text text-right">
          {{ $tagCount }}件
        </div>
      </div>
    </div>
    
    <user-hobby-inifinite
      page-type='hobbies'
     :authorized-id='@json(Auth::id())'
     :authorized-check='@json(Auth::check())'
     :test='@json($tagName)'
     >
    </user-hobby-inifinite>
  </div>
@endsection
