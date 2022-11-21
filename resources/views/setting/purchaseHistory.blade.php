@extends('app')

@section('title', '設定')

@section('content')
@section('breadcrumbs', Breadcrumbs::render('purchaseHistory'))
  <div class="container mb-5">
    <h4 class="my-4">【Q手購入履歴】</h4>
    <ul class="list-group">
    @foreach($user->purchase as $purchase)
      @if($purchase->item == 'setQutte')
      <li class="list-group-item mb-3">
        <div style="display: flex;justify-content: space-between;">
          <p style="margin-bottom: 0;">購入日時 {{ $purchase->created_at->format('Y/m/d H:i:s') }} </p>
          <p style="margin-bottom: 0;">@if($purchase->done == '0') 未発送 @else 発送済み @endif</p>
        </div>
        <hr>
        <p>3枚セット × {{ $purchase->quantity }}　　合計 {{ $purchase->totalPrice }}円</p>
      </li>
      @elseif($purchase->item == 'singleQutte')
      <li class="list-group-item mb-3">
        <div style="display: flex;justify-content: space-between;">
          <p style="margin-bottom: 0;">購入日時 {{ $purchase->created_at->format('Y/m/d H:i:s') }} </p>
          <p style="margin-bottom: 0;">@if($purchase->done == '0') 未発送 @else 発送済み @endif</p>
        </div>
        <hr>
        <p>Q手 {{ $purchase->quantity }}枚分　合計 {{ $purchase->totalPrice }}円</p>
      </li>
      @endif
    @endforeach
    </ul>
  </div>
@endsection