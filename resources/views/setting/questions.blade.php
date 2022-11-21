@extends('app')

@section('title', 'よくある質問')

@section('content')
@section('breadcrumbs', Breadcrumbs::render('questions'))
  <div class="container mb-5">
    <h4 class="my-4">【よくある質問】</h4>
    <questions-inifinite>
    </questions-inifinite>
  </div>
@endsection