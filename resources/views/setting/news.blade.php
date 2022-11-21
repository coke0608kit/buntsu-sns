@extends('app')

@section('title', 'お知らせ')

@section('content')
@section('breadcrumbs', Breadcrumbs::render('news'))
  <div class="container mb-5">
    <h2 class="h5 mt-4">{{ $information->title }}</h2>
    <p class="mb-5" style="text-align: right;">{{ mb_substr($information->created_at, 0, 4) }}年{{ mb_substr($information->created_at, 5, 2) }}月{{ mb_substr($information->created_at, 8, 2) }}日</p>
    <p style="line-height: 26px;">{!! $information->content !!}</a>
  </div>
@endsection

