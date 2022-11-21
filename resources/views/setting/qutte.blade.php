@extends('app')

@section('title', '設定')

@section('content')
@section('breadcrumbs', Breadcrumbs::render('setting'))
  <div class="container mb-5">
    <h4 class="my-4">Q手設定内容の確認</h4>
    <div>
      設定したQ手の内容（宛先）を確認できます。
      <confirm-qutte-reader>
      </confirm-qutte-reader>
    </div>
  </div>
@endsection

