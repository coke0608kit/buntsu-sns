@extends('app')

@section('title', '特定商取引法に基づく表記')

@section('content')
@section('breadcrumbs', Breadcrumbs::render('tokushoho'))
  <div class="container mb-5">
    <h1>特定商取引法に基づく表記</h1>
    <h2>1.事業者の名称</h2>
    <p class="text">BUNTSU</p>
    <h2>2.代表者</h2>
    <p class="text">道下　真人</p>
    <h2>3.住所</h2>
    <p class="text">東京都中央区銀座8-10-5 DENSANビル G-42473</p>
    <h2>4.お問合せ先</h2>
    <p class="text">contact@buntsu-sns.com</p>
    <h2>5.商品代金</h2>
    <p class="text">ユーザーは，本サービスの利用にあたり，以下の行為をしてはなりません。</p>
    <h2>6.商品代金以外の必要料金</h2>
    <p class="text">なし</p>
    <h2>7.お支払い方法</h2>
    <p class="text">クレジットカード決済</p>
    <h2>8.代金のお支払い時期</h2>
    <p class="text">都度課金</p>
    <p class="list text">購入時</p>
    <p class="text">定期課金</p>
    <p class="list text">初回購入時、翌月以降毎月1日請求</p>
    <h2>9.商品の引き渡し時期</h2>
    <p class="text">ご注文後、ただちにサービスをご利用いただけます。（商品のQ手については注文後1週間以内に発送します。）</p>
    <h2>10.返品（キャンセル）・解約について</h2>
    <p class="text">商品の性質上、ご購入完了後の返品（キャンセル）はお受けできません。ご了承ください。</p>
    <p class="text">定期課金商品の解約は、いつでも会員ページから行えます。次回決済日までに解約していただければ、次回以降の請求は発生いたしません。なお、日割り分等での返金を承ることはできません。ご了承ください。</p>

    <p class="mb-5 text-right text">以上</p>
  </div>
@endsection

<style scoped>
h1 {
    text-align: center !important;
    margin: 2rem auto !important;
    font-size: 1.5rem !important;
    font-weight: bold !important;
}
h2 {
    text-align: left;
    font-size: 1.0rem !important;
    line-height: 1.6 !important;
}
.text {
    display: block;
    text-align: left;
    line-height: 1.8;
    font-size: 0.8rem !important;
}
.list {
    padding-left: 1rem;
}
</style>