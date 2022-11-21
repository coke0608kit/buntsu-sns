@extends('app')

@section('title', 'クレジットカード管理')

@section('content')
@section('breadcrumbs', Breadcrumbs::render('credit'))
  <div class="container mb-5">
    <h4 class="my-4">クレジットカード管理</h4>
    @if (session('error-message'))
      <p>{{ session('error-message') }}</p>
    @endif
    @if ($message ?? '' != '')
      <flash-message
          message={{ $message ?? '' }}
        >  
      </flash-message>
    @endif
    
    @if ($user->profile->zipcode1 == null || $user->profile->zipcode2 == null || $user->profile->address1 == null || $user->profile->address2 == null)
      <form action="{{ route('credit.address') }}" method="post">
        @csrf
        <zipcode-search class=""
          setpref='@json($user->profile->pref)'
          setzipcode1='@json($user->profile->zipcode1)'
          setzipcode2='@json($user->profile->zipcode2)'
          setaddress1='@json($user->profile->address1)'
          setaddress2='@json($user->profile->address2)'
        >
        </zipcode-search>
        <div class="w-100 d-flex mt-4">
          <button class="btn btn-primary mx-auto" type="submit">住所を登録する</button>
        </div>
      </form>
    @else
        <form action="{{ route('credit.store') }}" method="post">
          @csrf
          <payjp-form
            publickey={{ config('payjp.public_key') }}
          >
          </payjp-form>
        </form>

        @if (!empty($cardList))
          <p style="text-align: center;">もしくは登録済みのカードで支払い</p>
          <form action="{{ route('credit.store') }}" method="post" style="text-align: center;" id='checkout'>
            @csrf
            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons" style="flex-direction: column;align-items: center;max-width:330px">
              @foreach ($cardList as $card)
                <label class="btn  my-3 d-flex  flex-column  mx-0" style="height: 170px;border-radius: 5px;">
                  <div style="height: inherit;"></div>
                  <input type="radio" name="payjp_card_id" id="option1" autocomplete="off" value="{{ $card['id'] }}"> 
                    @if ($default_card == $card['id'])
                      <p class="mb-4" style="font-weight: bold;">メインカード</p>
                    @endif
                  <p class="h4">{{ $card['cardNumber'] }}</p>
                  <div class="d-flex flex-row justify-content-center">
                    <p class="mb-0 mr-3">{{ $card['name'] }}</p>
                    <p class="mb-0">{{ sprintf('%02d', $card['exp_month']) }}/{{ substr($card['exp_year'] , -2) }}</p>
                  </div>
                  <span class="mb-0 h5 ml-auto">{{ $card['brand'] }}</span>
                </label>
              @endforeach
            </div>
            <button class="btn btn-primary" type="submit" name="submit">選択したカードを月額支払いに使う</button>
            @if (count($cardList) > 1)
              <button class="btn btn-danger" type="submit" name="submit" value="delete">選択したカードを削除する</button>
            @endif
          </form>
        @else
          <p>カード情報を入力してください</p>
        @endif
    @endif
  </div>
@endsection

<style>
  label.btn.waves-effect.waves-light.active {
    background-color: #ffc68e;
  }

  .btn {
    box-shadow: 0 2px 5px 0 rgb(0 0 0 / 21%), 0 2px 10px 0 rgb(0 0 0 / 38%);
  }
</style>
