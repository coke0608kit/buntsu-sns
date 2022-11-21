@extends('app')

@section('title', 'プラン確認/変更')

@section('content')
@section('breadcrumbs', Breadcrumbs::render('plan'))
  <div class="container mb-5">
    @if ($message ?? '' != '')
      <flash-message
          message={{ $message ?? '' }}
        >  
      </flash-message>
    @endif
    <h4 class="py-4">【プラン確認/変更】</h4>
    <h5 >プラン一覧</h5>
    <table class="table">
      <thead>
        <tr>
          <th scope="col"></th>
          <th scope="col">フリー</th>
          <th scope="col">ライト</th>
          <th scope="col">スタンダード</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row" class="align-middle">月額</th>
          <td class="align-middle">0円</td>
          <td class="align-middle">550円</td>
          <td class="align-middle">1,100円</td>
        </tr>
        <tr>
          <th scope="row" class="align-middle">手紙の<br>受信数</th>
          <td class="align-middle">無制限</td>
          <td class="align-middle">無制限</td>
          <td class="align-middle">無制限</td>
        </tr>
        <tr>
          <th scope="row" class="align-middle">Q手配布数</th>
          <td class="align-middle">0枚</td>
          <td class="align-middle">毎月2枚</td>
          <td class="align-middle">毎月5枚</td>
        </tr>
        <tr>
          <th scope="row" class="align-middle">発送時期</th>
          <td class="align-middle">10日または25日 ※1</td>
          <td class="align-middle">25日のみ</td>
          <td class="align-middle">10日と25日 ※2</td>
        </tr>
      </tbody>
    </table>
        <p class="mb-1" style="font-size: 13px;">※1…受信があった時のみ発送されます。</p>
        <p class="mb-0" style="font-size: 13px;">※2…25日は必ず発送。10日は受信があった時だけ発送されます。</p>

    <div>
      <h5 class="mt-5 mb-1">プラン状況/プランの変更</h5>
      @if ($user->plan == 'free')
        <p>現在のプラン フリー</p>
        <h6>●できること</h6>
        <p>Q手を3枚セット1,100円で購入するかライト/スタンダードプランのユーザから譲ってもらうことで文通したい相手に手紙をおくることができます。</p>
        <a href="{{ route('payment.setQutte') }}" class="text-dark">
          <div class="card mt-3">
            <div class="card-body">
              <h5 class="card-title">単品でQ手を購入する</h5>
              <p class="card-text">Q手3枚セット1,100円で購入することができます。Q手1枚につき、1通を送ることができます。<br>※返信用封筒は付属しません。</p>
            </div>
          </div>
        </a>
        <a href="{{ route('payment.lite') }}" class="text-dark">
          <div class="card mt-3">
            <div class="card-body">
              <h5 class="card-title">ライトプランに加入する</h5>
              <p class="card-text">月額550円でQ手2枚と返信用封筒1つが毎月届きます。のんびり文通を続けたい方におすすめです。<br>※別途、初回のみ初期費用として550円がかかります。過去にライトプラン/スタンダードプランに入ったことがあっても、再入会の度に初期費用がかかります。</p>
            </div>
          </div>
        </a>
        <a href="{{ route('payment.standard') }}" class="text-dark">
          <div class="card mt-3">
            <div class="card-body">
              <h5 class="card-title">スタンダードプランに加入する</h5>
              <p class="card-text">月額1,100円でQ手5枚と返信用封筒2つが毎月届きます。積極的に文通をしたい方におすすめです。<br>※別途、初回のみ初期費用として550円がかかります。過去にライトプラン/スタンダードプランに入ったことがあっても、再入会の度に初期費用がかかります。</p>
            </div>
          </div>
        </a>
      @elseif ($user->plan == 'lite')
        <p>
          現在のプラン ⇒ ライトプラン<br>
          @if ($user->planStatus == 0)
            プラン加入日時 ⇒ {{ date('Y/m/d H:i', $su['start']) }}<br>
            次の更新タイミング ⇒ {{ date('Y/m/d H:i', $su['current_period_end']) }}<br>
            @isset($su['next_cycle_plan']->id)
              @if ($su['next_cycle_plan']->id == 'lite')
                来月のプラン ⇒ ライトプラン
              @else
                来月のプラン ⇒ スタンダードプラン
              @endif
            @endisset
          @else
            解約手続き中<br>
            今月末で解約予定
          @endif
        </p>
        <h6>●できること</h6>
        <p>Q手2枚と返信用封筒1つが毎月届きます。Q手が足りないときは1枚330円で購入することで追加で文通したい相手に手紙をおくることができます。</p>
        @if (\Carbon\Carbon::now()->format("j") <= 16 && $user->firsts->last()->created_at->format('Ym') != \Carbon\Carbon::now()->format('Ym'))
        <a href="{{ route('payment.singleQutte') }}" class="text-dark">
          <div class="card mt-3">
            <div class="card-body">
              <h5 class="card-title">単品でQ手を購入する</h5>
              <p class="card-text">Q手1枚につき330円で購入することができます。ライトプランの毎月の郵便にて同封します。</p>
            </div>
          </div>
        </a>
        @else
          <div class="card mt-3" style="background: #bbb;">
            <div class="card-body">
              <h5 class="card-title">単品でQ手を購入する</h5>
              <p class="card-text">毎月1日から16日の間だけ購入可能です。また、初回入会月はQ手の単品購入ができません。</p>
            </div>
          </div>
        @endif
        @if ($user->planStatus == 0)
        <a href="{{ route('payment.cancelLite') }}" class="text-dark">
          <div class="card mt-3">
            <div class="card-body">
              <h5 class="card-title">ライトプランを解約する</h5>
              <p class="card-text">ライトプランを解約し、フリープランに変更することができます。フリープラン変更後は、Q手が手元にあれば、引き続き文通することが可能です。また解約手続きをすると月末まで契約が続き、次の更新タイミング自動解約されます。</p>
            </div>
          </div>
        </a>
          @isset($su['next_cycle_plan']->id)
            @if ($su['next_cycle_plan']->id == 'standard')
              <div class="card mt-3" style="background: #bbb;">
                <div class="card-body">
                  <h5 class="card-title">スタンダードプランに変更する</h5>
                  <p class="card-text">既に次回のプランがライトプランに設定されています。</p>
                </div>
              </div>
            @else
              <a href="{{ route('payment.changeStandard') }}" class="text-dark">
                <div class="card mt-3">
                  <div class="card-body">
                    <h5 class="card-title">スタンダードプランに変更する</h5>
                    <p class="card-text">月額1,100円でQ手5枚と返信用封筒2つが毎月届きます。積極的に文通をしたい方におすすめです。</p>
                  </div>
                </div>
              </a>
            @endif
          @else
            <a href="{{ route('payment.changeStandard') }}" class="text-dark">
              <div class="card mt-3">
                <div class="card-body">
                  <h5 class="card-title">スタンダードプランに変更する</h5>
                  <p class="card-text">月額1,100円でQ手5枚と返信用封筒2つが毎月届きます。積極的に文通をしたい方におすすめです。</p>
                </div>
              </div>
            </a>
          @endisset
        @else
        <a href="{{ route('payment.restartLite') }}" class="text-dark">
          <div class="card mt-3">
            <div class="card-body">
              <h5 class="card-title">ライトプランを再開する</h5>
              <p class="card-text">解約後、やっぱり翌月もライトプランを続けたい場合、初回手数料なしで復帰が可能です。解約手続きをした月のみ可能な処理です。</p>
            </div>
          </div>
        </a>
        <div class="card mt-3" style="background: #bbb;">
          <div class="card-body">
            <h5 class="card-title">スタンダードプランに変更する</h5>
            <p class="card-text">ライトプラン解約中はスタンダードプランへの変更ができません。ライトプランが解約される翌月まで待つか、ライトプランを再開してからスタンダードプランへの変更をするかを検討してください。いずれの場合も別途料金の発生はありません。</p>
          </div>
        </div>
        @endif
      @elseif ($user->plan == 'standard')
        <p>
          現在のプラン ⇒ スタンダードプラン<br>
          @if ($user->planStatus == 0)
            プラン加入日時 ⇒ {{ date('Y/m/d H:i', $su['start']) }}<br>
            次の更新タイミング ⇒ {{ date('Y/m/d H:i', $su['current_period_end']) }}<br>
            @isset($su['next_cycle_plan']->id)
              @if ($su['next_cycle_plan']->id == 'lite')
                来月のプラン ⇒ ライトプラン
              @else
                来月のプラン ⇒ スタンダードプラン
              @endif
            @endisset
          @else
            解約手続き中<br>
            今月末で解約予定
          @endif
        </p>
        <h6>●できること</h6>
        <p>Q手5枚と返信用封筒2つが毎月届きます。Q手が足りないときは1枚330円で購入することで追加で文通したい相手に手紙をおくることができます。</p>
        @if (\Carbon\Carbon::now()->format("j") <= 16 && $user->firsts->last()->created_at->format('Ym') != \Carbon\Carbon::now()->format('Ym'))
        <a href="{{ route('payment.singleQutte') }}" class="text-dark">
          <div class="card mt-3">
            <div class="card-body">
              <h5 class="card-title">単品でQ手を購入する</h5>
              <p class="card-text">Q手1枚につき330円で購入することができます。スタンダードプランの定期便にて同封します。</p>
            </div>
          </div>
        </a>
        @else
          <div class="card mt-3" style="background: #bbb;">
            <div class="card-body">
              <h5 class="card-title">単品でQ手を購入する</h5>
              <p class="card-text">毎月1日から16日の間だけ購入可能です。また、初回入会月はQ手の単品購入ができません。</p>
            </div>
          </div>
        @endif
        @if ($user->planStatus == 0)
          
        @isset($su['next_cycle_plan']->id)
          @if ($su['next_cycle_plan']->id == 'lite')
            <div class="card mt-3" style="background: #bbb;">
              <div class="card-body">
                <h5 class="card-title">ライトプランに変更する</h5>
                <p class="card-text">既に次回のプランがライトプランに設定されています。</p>
              </div>
            </div>
          @else
            <a href="{{ route('payment.changeLite') }}" class="text-dark">
              <div class="card mt-3">
                <div class="card-body">
                  <h5 class="card-title">ライトプランに変更する</h5>
                  <p class="card-text">月額550円でQ手2枚と返信用封筒1つが毎月届きます。のんびり文通を続けたい方におすすめです。</p>
                </div>
              </div>
            </a>
          @endif
        @else
          <a href="{{ route('payment.changeLite') }}" class="text-dark">
            <div class="card mt-3">
              <div class="card-body">
                <h5 class="card-title">ライトプランに変更する</h5>
                <p class="card-text">月額550円でQ手2枚と返信用封筒1つが毎月届きます。のんびり文通を続けたい方におすすめです。</p>
              </div>
            </div>
          </a>
        @endisset
        <a href="{{ route('payment.cancelStandard') }}" class="text-dark">
          <div class="card mt-3">
            <div class="card-body">
              <h5 class="card-title">スタンダードプランを解約する</h5>
              <p class="card-text">スタンダードプランを解約し、フリープランに変更することができます。フリープラン変更後は、Q手が手元にあれば、引き続き文通することが可能です。また解約手続きをすると月末まで契約が続き、次の更新タイミング自動解約されます。</p>
            </div>
          </div>
        </a>
        @else
        <div class="card mt-3" style="background: #bbb;">
          <div class="card-body">
            <h5 class="card-title">ライトプランに変更する</h5>
            <p class="card-text">スタンダードプラン解約中はライトプランへの変更ができません。スタンダードプランが解約される翌月まで待つか、スタンダードプランを再開してからライトプランへの変更をするかを検討してください。いずれの場合も別途料金の発生はありません。</p>
          </div>
        </div>
        <a href="{{ route('payment.restartStandard') }}" class="text-dark">
          <div class="card mt-3">
            <div class="card-body">
              <h5 class="card-title">スタンダードプランを再開する</h5>
              <p class="card-text">解約後、やっぱり翌月もスタンダードプランを続けたい場合、初回手数料なしで復帰が可能です。解約手続きをした月のみ可能な処理です。</p>
            </div>
          </div>
        </a>
        @endif
      @endif
    </div>
    <p class='mt-3 mb-0'>※金額はすべて税込みです。</p>
  </div>
@endsection