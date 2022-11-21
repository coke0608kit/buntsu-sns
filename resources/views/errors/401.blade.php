@extends('app')

@section('title', '401エラー')

@section('content')

<div class="container my-4">
<p class="error-message">エラーページです。</p>
<p class="error-detail">自動でトップページに戻ります。3秒たってもページが変わらない場合は下のリンクをタップしてください。</p>
<p><a href="{{env('APP_URL')}}">&gt;&gt;トップページ</a></p>
</div>
@endsection

<script>
window.onload=function(){
setTimeout(redirectPage(),3000);
}
function redirectPage() {
    window.location.href = '/'
}
</script>