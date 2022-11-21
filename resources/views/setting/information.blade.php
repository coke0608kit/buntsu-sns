@extends('app')

@section('title', 'お知らせ一覧')

@section('content')
@section('breadcrumbs', Breadcrumbs::render('information'))
  <div class="container mb-5">
    <h4 class="my-4">【お知らせ一覧】</h4>
    <information-inifinite>
    </information-inifinite>
  </div>
@endsection