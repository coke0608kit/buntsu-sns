@extends('app')

@section('title', '設定')

@section('content')
@section('breadcrumbs', Breadcrumbs::render('delete'))
  <div class="container mb-5">
    <h4 class="my-4">退会</h4>
    <p>退会をするとプロフィールや投稿した画像、いいね、やりとり履歴、購入履歴、月額課金、クレジットカード情報、ありとあらゆるすべての情報が削除されます。一度削除されたアカウントは二度とログインすることはできません。</p>
    <p>それでも良い場合は下の退会ボタンを押してください。</p>
    <form action="{{ route('setting.delete') }}" method="POST" style="text-align: center;" class="pt-4">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn btn-danger">退会する</button>
    </form>
  </div>
@endsection