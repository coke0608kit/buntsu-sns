@extends('app')

@section('title', '設定')

@section('content')
@section('breadcrumbs', Breadcrumbs::render('setting'))
  <div class="container mb-5">
    <h4 class="my-4">【設定】</h4>
    <ul class="list-group">
      <li class="list-group-item">
        <a href="{{ route("setting.profile") }}">
          <div>プロフィール</div>
        </a>
      </li>
      <li class="list-group-item">
        <a href="{{ route("setting.history") }}">
          <div>やりとり</div>
        </a>
      </li>
      <li class="list-group-item">
        <a href="{{ route("setting.howtouse") }}">
          <div>使い方</div>
        </a>
      </li>
      <li class="list-group-item">
        <a href="{{ route("setting.plan") }}">
          <div>プラン確認/変更</div>
        </a>
      </li>
      <li class="list-group-item">
        <a href="{{ route("setting.credit") }}">
          <div>クレジットカード管理</div>
        </a>
      </li>
      <li class="list-group-item">
        <a href="{{ route("setting.purchaseHistory") }}">
          <div>Q手購入履歴</div>
        </a>
      </li>
      <li class="list-group-item">
        <a href="{{ route("profile.block") }}">
          <div>ブロックリスト</div>
        </a>
      </li>
      <li class="list-group-item">
        <a href="{{ route("setting.qutte") }}">
          <div>Q手設定内容の確認</div>
        </a>
      </li>
      <li class="list-group-item">
        <a href="https://lin.ee/MdRNjGg">
          <div>お問い合わせ</div>
        </a>
      </li>
      <li class="list-group-item">
        <a href="{{ route("setting.deleteConfirm") }}">
          <div>退会</div>
        </a>
      </li>
    </ul>
    
    <ul class="list-group mt-5">
      <li class="list-group-item">
        <a href="{{ route("setting.sponsers") }}">
          <div>スポンサー</div>
        </a>
      </li>
      <li class="list-group-item">
        <a href="{{ route("rule.terms") }}">
          <div>利用規約</div>
        </a>
      </li>
      <li class="list-group-item">
        <a href="{{ route("rule.privacy") }}">
          <div>プライバシーポリシー</div>
        </a>
      </li>
      <li class="list-group-item">
        <a href="{{ route("rule.tokushoho") }}">
          <div>特定商取引法に基づく表記</div>
        </a>
      </li>
  </div>
@endsection

