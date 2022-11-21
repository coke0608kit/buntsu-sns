@extends('app')

@section('title', 'プラン確認/変更')

@section('content')
@section('breadcrumbs', Breadcrumbs::render('payment'))
  <div class="container mb-5">
    @if (session('error-message'))
      <p>{{ session('error-message') }}</p>
    @endif
    @if ($message ?? '' != '')
      <flash-message
          message={{ $message ?? '' }}
        >  
      </flash-message>
    @endif
    
    @if ($type == 'setQutte')
      <div class="card mt-3">
        <div class="card-body">
          <h5 class="card-title">単品でQ手を購入する</h5>
          <p class="card-text">Q手3枚セット1,100円で購入することができます。Q手1枚につき、1通を送ることができます。<br>※返信用封筒は付属しません。</p>
          <dynamic-price
            price="1100"
            item="setQutte"
          >
          </dynamic-price>
        </div>
      </div>
      
    @elseif ($type == 'lite')
      <div class="card mt-3">
        <div class="card-body">
          <h5 class="card-title">ライトプランに加入する</h5>
          <p class="card-text">月額550円でQ手2枚と返信用封筒1つが毎月届きます。のんびり文通を続けたい方におすすめです。※別途、初回のみ初期費用として550円がかかります。過去にライトプラン/スタンダードプランに入ったことがあっても、再入会の度に初期費用がかかります。</p>
          <p class="card-text">お支払額 1,100円（初期費用,送料/消費税込）<br>以後、今日から一か月毎の同日にに支払いにつかったクレジットカードから550円(税込)を清算させていただきます。</p>
        </div>
      </div>
    @elseif ($type == 'standard')
      <div class="card mt-3">
        <div class="card-body">
          <h5 class="card-title">スタンダードプランに加入する</h5>
          <p class="card-text">月額1,100円でQ手5枚と返信用封筒2つが毎月届きます。積極的に文通をしたい方におすすめです。※別途、初回のみ初期費用として550円がかかります。過去にライトプラン/スタンダードプランに入ったことがあっても、再入会の度に初期費用がかかります。</p>
          <p class="card-text">お支払額 1,650円（初期費用,送料/消費税込）<br>以後、今日から一か月毎の同日にに支払いにつかったクレジットカードから1,100円(税込)を清算させていただきます。</p>
        </div>
      </div>
    @elseif ($type == 'singleQutte')
      <div class="card mt-3">
        <div class="card-body">
          <h5 class="card-title">単品でQ手を購入する</h5>
          <p class="card-text">Q手1枚につき330円で購入することができます。定期便にて同封します。</p>
          <dynamic-price
            price="330"
            item="singleQutte"
          >
          </dynamic-price>
        </div>
      </div>
    @elseif ($type == 'cancelLite')
      <div class="card mt-3">
        <div class="card-body">
          <h5 class="card-title">ライトプランを解約する</h5>
          <p class="card-text">ライトプランを解約し、フリープランに変更することができます。フリープラン変更後は、Q手が手元にあれば、引き続き文通することが可能です。またスタンダードプランへ加入しなおすこともできます。</p>
        </div>
      </div>
    @elseif ($type == 'restartLite')
      <div class="card mt-3">
        <div class="card-body">
          <h5 class="card-title">ライトプランを再開する</h5>
          <p class="card-text">解約後、やっぱり翌月もライトプランを続けたい場合、初回手数料なしで復帰が可能です。解約手続きをした月のみ可能な処理です。</p>
          <p class="card-text">お支払額 550円（送料/消費税込）<br>翌月１日から一か月毎に支払いにつかったクレジットカードから550円(税込)を清算させていただきます。</p>
        </div>
      </div>
    @elseif ($type == 'changeStandard')
      <div class="card mt-3">
        <div class="card-body">
          <h5 class="card-title">スタンダードプランに変更する</h5>
          <p class="card-text">ライトプランを解約し、スタンダードプランへ変更することができます。スタンダードプランは月額1,100円でQ手5枚と返信用封筒2つが毎月届きます。積極的に文通をしたい方におすすめです。※お支払い済みスタンダードプランの月会費は返金できませんので、ご了承ください。</p>
          <p class="card-text">お支払額 1,100円（送料/消費税込）<br>以後、今日から一か月毎の同日にに支払いにつかったクレジットカードから1,100円(税込)を清算させていただきます。</p>
        </div>
      </div>
    @elseif ($type == 'changeLite')
      <div class="card mt-3">
        <div class="card-body">
          <h5 class="card-title">ライトプランに変更する</h5>
          <p class="card-text">スタンダードプランを解約し、ライトプランへ変更することができます。ライトプランは月額550円でQ手2枚と返信用封筒1つが毎月届きます。のんびり文通を続けたい方におすすめです。※お支払い済みスタンダードプランの月会費は返金できませんので、ご了承ください。</p>
          <p class="card-text">お支払額 550円（送料/消費税込）<br>以後、今日から一か月毎の同日にに支払いにつかったクレジットカードから550円(税込)を清算させていただきます。</p>
        </div>
      </div>
    @elseif ($type == 'cancelStandard')
      <div class="card mt-3">
        <div class="card-body">
          <h5 class="card-title">スタンダードプランを解約する</h5>
          <p class="card-text">スタンダードプランを解約し、フリープランに変更することができます。フリープラン変更後は、Q手が手元にあれば、引き続き文通することが可能です。またライトプランへ加入しなおすこともできます。</p>
        </div>
      </div>
    @elseif ($type == 'restartStandard')
      <div class="card mt-3">
        <div class="card-body">
          <h5 class="card-title">スタンダードプランを再開する</h5>
          <p class="card-text">解約後、やっぱり翌月もスタンダードプランを続けたい場合、初回手数料なしで復帰が可能です。解約手続きをした月のみ可能な処理です。</p>
          <p class="card-text">お支払額 1,100円（送料/消費税込）<br>翌月１日から一か月毎に支払いにつかったクレジットカードから1,100円(税込)を清算させていただきます。</p>
        </div>
      </div>
    @endif
    
    @if ($user->profile->zipcode1 == null || $user->profile->zipcode2 == null || $user->profile->address1 == null || $user->profile->address2 == null)
      <form action="{{ route('payment.address') }}" method="post" class="mt-3">
        @csrf
        <zipcode-search class=""
          setpref='@json($user->profile->pref)'
          setzipcode1='@json($user->profile->zipcode1)'
          setzipcode2='@json($user->profile->zipcode2)'
          setaddress1='@json($user->profile->address1)'
          setaddress2='@json($user->profile->address2)'
        >
        </zipcode-search>

        <div class="mb-3">
          <p class="mb-0">氏名</p>
          <input type="text" value="{{ $user->profile->realname != '' ? $user->profile->realname : old('realname') }}" name="realname">
        </div>
        <input type="hidden" name="type" value="{{ $type }}">
        <div class="w-100 d-flex mt-4">
          <button class="btn btn-primary mx-auto" type="submit">住所を登録する</button>
        </div>
      </form>
    @else
      @if ($type == 'cancelLite' || $type == 'cancelStandard')
          <form class="mt-4" action="{{ route('payment.store') }}" method="post" style="text-align: center;">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">
            <button class="btn btn-danger" type="submit" name="submit">解約する</button>
          </form>
      @else
        <form action="{{ route('payment.store') }}" method="post">
          @csrf
          <payjp-form
            publickey={{ config('payjp.public_key') }}
          >
          </payjp-form>
          <input type="hidden" name="type" value="{{ $type }}">
        </form>

        @if (!empty($cardList))
          <p style="text-align: center;">もしくは登録済みのカードで支払い</p>
          <form action="{{ route('payment.store') }}" method="post" style="text-align: center;" id='checkout'>
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
            <button class="btn btn-primary" type="submit" name="submit">選択したカードで決済する</button>
            @if (count($cardList) > 1)
              <button class="btn btn-danger" type="submit" name="submit" value="delete">選択したカードを削除する</button>
            @endif
            <input type="hidden" name="type" value="{{ $type }}">
          </form>
        @else
          <p>カード情報を入力してください</p>
        @endif
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
