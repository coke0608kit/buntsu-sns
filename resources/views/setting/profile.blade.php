@extends('app')

@section('title', 'プロフィール')

@section('content')
@section('breadcrumbs', Breadcrumbs::render('profile'))
  <div class="container mb-5">
    <h4 class="my-4">【プロフィール設定】</h4>
    
    <div id="form">
      <div>
        アイコン
        <article-photos-input
          seticon={{ $user->profile->icon != '' ? $user->profile->icon  : "" }}
        >  
        </article-photos-input>
      </div>

      <div class="mb-3">
        <p class="mb-0">ニックネーム</p>
        <input type="text" value="{{ $user->nickname != '' ? $user->nickname : old('nickname') }}" name="nickname">
      </div>
      
      <div>
        <p class="mb-0">性別</p>
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
          <label class="btn  {{ $user->profile->gender == 1 ? "active" : "" }}">
            <input type="radio" name="gender" id="option1" autocomplete="off" value="1" {{ $user->profile->gender == 1 ? "checked" : "" }}> 男性
          </label>
          <label class="btn  {{ $user->profile->gender == 2 ? "active" : "" }}">
            <input type="radio" name="gender" id="option2" autocomplete="off" value="2" {{ $user->profile->gender == 2 ? "checked" : "" }}> 女性
          </label>
          <label class="btn  {{ $user->profile->gender == 9 ? "active" : "" }}">
            <input type="radio" name="gender" id="option3" autocomplete="off" value="9" {{ $user->profile->gender == 9 ? "checked" : "" }}> 未回答
          </label>
        </div>
      </div>

      <birthday-setting  class="my-3"
        setyear='@json($user->profile->year)'
        setmonth='@json($user->profile->month)'
        setday='@json($user->profile->day)'
      >
      </birthday-setting>

      @if ($user->plan == 'free')
        @if ($user->profile->realname == null || $user->profile->zipcode1 == null || $user->profile->zipcode2 == null || $user->profile->address1 == null || $user->profile->address2 == null || $user->profile->realname == null)
          <div style="padding: 10px;background: #f344;">
            <p>フリープランでも住所を登録していれば、手紙の送受信が可能です。</p>
        @endif
      @endif
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
      @if ($user->plan == 'free')
        @if ($user->profile->realname == null || $user->profile->zipcode1 == null || $user->profile->zipcode2 == null || $user->profile->address1 == null || $user->profile->address2 == null || $user->profile->realname == null)
          </div>
        @endif
      @endif

      <div class="mt-3">
        趣味（5つまで）
        <article-tags-input
          :initial-tags='@json($hobbyNames ?? [])'
          :autocomplete-items='@json($allHobbyNames ?? [])'
        >
        </article-tags-input>
      </div>

      <div class="mt-3">
        プロフィール（自由文）
        <textarea name="profile" id="" cols="30" rows="10" class="w-100">{{ $user->profile->profile }}</textarea>
      </div>
      
      <div class="mt-3">
        <p class="mb-0">送受信可能な性別</p>
        <p class="mb-0">（選択した性別の人とだけ文通が出来ます）</p>
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
          <label class="btn  {{ $user->profile->canSendGender == 1 ? "active" : "" }}">
            <input type="radio" name="canSendGender" id="option1" autocomplete="off" value="1" {{ $user->profile->canSendGender == 1 ? "checked" : "" }}> 男性
          </label>
          <label class="btn  {{ $user->profile->canSendGender == 2 ? "active" : "" }}">
            <input type="radio" name="canSendGender" id="option2" autocomplete="off" value="2" {{ $user->profile->canSendGender == 2 ? "checked" : "" }}> 女性
          </label>
          <label class="btn  {{ $user->profile->canSendGender == 9 ? "active" : "" }}">
            <input type="radio" name="canSendGender" id="option3" autocomplete="off" value="9" {{ $user->profile->canSendGender == 9 ? "checked" : "" }}> 設定しない
          </label>
        </div>
      </div>
      
      <div class="mt-3">
        <p class="mb-0">募集状況</p>
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
          <label class="btn  {{ $user->profile->status === false ? "active" : "" }}">
            <input type="radio" name="status" id="option1" autocomplete="off" value="false" {{ $user->profile->status === false ? "checked" : "" }}> 募集停止
          </label>
          <label class="btn  {{ $user->profile->status === true ? "active" : "" }}">
            <input type="radio" name="status" id="option2" autocomplete="off" value="true" {{ $user->profile->status === true ? "checked" : "" }}> 募集中
          </label>
        </div>
      </div>
      
      <div class="mt-3">
        <p class="mb-0">やり取りの頻度</p>
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
          <label class="btn  {{ $user->profile->condition === false ? "active" : "" }}">
            <input type="radio" name="condition" id="option1" autocomplete="off" value="false" {{ $user->profile->condition === false ? "checked" : "" }}> テキパキ
          </label>
          <label class="btn  {{ $user->profile->condition === true ? "active" : "" }}">
            <input type="radio" name="condition" id="option2" autocomplete="off" value="true" {{ $user->profile->condition === true ? "checked" : "" }}> まったり
          </label>
        </div>
      </div>
      
      <submit-profile
        authid = "{{ $user->id }}"
      >
      </submit-profile>
    </div>
    
    <div style="display:none" class="loading">
      <div class="loader">
      </div>
      更新中
    </div>
    <div style="display:none" class="message">
    </div>
  </div>
@endsection

<style>
  label.btn.waves-effect.waves-light.active {
    background-color: #ffc68e;
  }
</style>

<style scoped>
.loader {
  color: #6c6b70;
  font-size: 90px;
  text-indent: -9999em;
  overflow: hidden;
  width: 1em;
  height: 1em;
  border-radius: 50%;
  margin: 72px auto;
  position: relative;
  -webkit-transform: translateZ(0);
  -ms-transform: translateZ(0);
  transform: translateZ(0);
  -webkit-animation: load6 1.7s infinite ease, round 1.7s infinite ease;
  animation: load6 1.7s infinite ease, round 1.7s infinite ease;
}
@-webkit-keyframes load6 {
  0% {
    box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
  }
  5%,
  95% {
    box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
  }
  10%,
  59% {
    box-shadow: 0 -0.83em 0 -0.4em, -0.087em -0.825em 0 -0.42em, -0.173em -0.812em 0 -0.44em, -0.256em -0.789em 0 -0.46em, -0.297em -0.775em 0 -0.477em;
  }
  20% {
    box-shadow: 0 -0.83em 0 -0.4em, -0.338em -0.758em 0 -0.42em, -0.555em -0.617em 0 -0.44em, -0.671em -0.488em 0 -0.46em, -0.749em -0.34em 0 -0.477em;
  }
  38% {
    box-shadow: 0 -0.83em 0 -0.4em, -0.377em -0.74em 0 -0.42em, -0.645em -0.522em 0 -0.44em, -0.775em -0.297em 0 -0.46em, -0.82em -0.09em 0 -0.477em;
  }
  100% {
    box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
  }
}
@keyframes load6 {
  0% {
    box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
  }
  5%,
  95% {
    box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
  }
  10%,
  59% {
    box-shadow: 0 -0.83em 0 -0.4em, -0.087em -0.825em 0 -0.42em, -0.173em -0.812em 0 -0.44em, -0.256em -0.789em 0 -0.46em, -0.297em -0.775em 0 -0.477em;
  }
  20% {
    box-shadow: 0 -0.83em 0 -0.4em, -0.338em -0.758em 0 -0.42em, -0.555em -0.617em 0 -0.44em, -0.671em -0.488em 0 -0.46em, -0.749em -0.34em 0 -0.477em;
  }
  38% {
    box-shadow: 0 -0.83em 0 -0.4em, -0.377em -0.74em 0 -0.42em, -0.645em -0.522em 0 -0.44em, -0.775em -0.297em 0 -0.46em, -0.82em -0.09em 0 -0.477em;
  }
  100% {
    box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
  }
}
@-webkit-keyframes round {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@keyframes round {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
</style>
<style lang="css">
  .vue-tags-input .ti-tag {
    background: transparent;
    border: 1px solid #747373;
    color: #747373;
    margin-right: 4px;
    border-radius: 0px;
    font-size: 13px;
  }
  .vue-tags-input .ti-tag::before {
    content: "#";
  }
</style>