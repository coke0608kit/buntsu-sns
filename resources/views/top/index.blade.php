@extends('app')

@section('title', 'トップページ')

@section('content')
  <div class="container mb-5" style="padding: 0">
    <img style="width: 100%;" src="{{ asset('images/top.png')}}">
    <div class='py-5' style="background: white;">
      <p style="text-align:center;font-size: 22px;">news</p>
      <table style="display: flex;margin: auto;width: 80%">
        <tbody>
          @foreach($information as $data)
              <tr>
                  <th style="margin-right: 10px;display: block;width: 110px;"><a href="/news/{{ $data->id }}" style="color:black;">{{ $data->created_at->format('Y年m月d日') }}</a></th>
                  <td style="text-align: left;"><a href="/news/{{ $data->id }}" style="color:black;">{{ $data->title }}</a></td>
              </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <img style="width: 100%;" src="{{ asset('images/hp_2.png')}}">
    <img style="width: 100%;" src="{{ asset('images/hp_3.png')}}">
    <img style="width: 100%;" src="{{ asset('images/hp_4.png')}}">
    <a href="{{ route('setting.howtouse') }}">
      <img style="width: 100%;" src="{{ asset('images/hp_5.png')}}">
    </a>
    <div class='py-5' style="background: white;">
      <p style="text-align:center;font-size: 22px;">よくある質問</p>
      <div class="accordion mb-5" id="accordion">
          @foreach($questions as $question)
            <div class=" QandA-1">
              <dl>
                <div id="heading_{{ $question['id'] }}" >
                  <dt style="text-align: left;border-bottom: 1px solid;" class="collapsed w-100" type="button" data-toggle="collapse" data-target="#collapse_{{ $question['id'] }}" aria-expanded="false" aria-controls="collapse_{{ $question['id'] }}">
                      {{ $question['question'] }}
                  </dt>
                </div>
                <dd id="collapse_{{ $question['id'] }}" class="collapse" aria-labelledby="heading_{{ $question['id'] }}" data-parent="#accordion">
                    {{ $question['answer'] }}
                </dd>
              </dl>
            </div>
          @endforeach
      </div>
      <a href="{{ route('setting.questions') }}">
        <div class="otherQuestion">
          そのほかの質問はコチラから
        </div>
      </a>
    </div>
  </div>
  <footer class="col-md-9 ml-sm-auto col-lg-10 px-0" style="margin: auto;">
    <div class="policy container" style="font-size: 14px;">
      <div class="row">
        <p class="col-6 col-sm-3"><a href="{{ route('rule.terms') }}" style="color:black;">利用規約</a></p>
        <p class="col-6 col-sm-3"><a href="{{ route('rule.privacy') }}" style="color:black;">プライバシーポリシー</a></p>
        <p class="col-6 col-sm-3"><a href="{{ route('rule.tokushoho') }}" style="color:black;">特定商取引法に基づく表記</a></p>
        <p class="col-6 col-sm-3"><a href="{{ route('setting.sponsers') }}" style="color:black;">スポンサー</a></p>
      </div>
    </div>
    <div class="copyright">
      <p>© 2022 BUNTSU</p>
    </div>
  </footer>
  @endsection

<style scoped>
#app {
    background: #FDF6F1;
    padding-bottom: 70px;
}
.container {
    max-width: 1024px !important;
}

.QandA-1 {
	width: 100%;
	font-size: 14px; /*全体のフォントサイズ*/
}
.QandA-1 h2 {

}
.QandA-1 dt {
	padding: 8px;
}
.QandA-1 dt:before {
	content: "Q.";
	margin-right: 8px;
}
.QandA-1 dd {
	margin: 24px 16px 40px 32px;
	line-height: 140%;
	text-indent: -24px;
}
.QandA-1 dd:before {
	content: "A.";
	margin-right: 8px;
}
#accordion {
  width: 85%;
  margin: auto;
}
.otherQuestion {
  text-align: center;
  width: 85%;
  margin: auto;
  font-size: 1rem;
  padding: 12px;
  border: solid 1px black;
  color: black;
}
.otherQuestion:hover {
  color: white;
  background: #E0653F;
  border: none;
}
footer .policy {
    max-width: 720px;
}
footer .copyright {
  margin-top: 2rem;
}
footer .copyright p {
  text-align: center;
}
.row {
  text-align: center;
}
</style>