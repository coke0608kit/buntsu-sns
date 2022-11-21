@if($user->official == 1)  
<div class="card mt-3 " style='background: #FFF2CC;'>
@else
<div class="card mt-3" >
@endif
  <div class="card-body">
    <div class="d-flex flex-row">
      <a href="{{ route('users.show', ['name' => $user->name]) }}" class="text-dark">
        @if ($user->profile->icon)
          <img class="mr-2" style="width: 45px;" src="{{ $user->profile->icon }}" alt="">
        @else
          <i class="fas fa-user-circle fa-3x mr-1"></i>
        @endif
      </a>
      <h2 class="h5 card-title m-0">
        <a href="{{ route('users.show', ['name' => $user->name]) }}" class="text-dark">
          {{ $user->nickname }}
        </a>
        <p class="card-text">
          @auth
            @if( !($user->isBlockedBy(Auth::user()) ||  Auth::user()->isBlockedBy($user)))
            {{ $user->profile->pref ?? '' }} {{ $user->profile->age ?? '' }} @if($user->profile->gender == 1){{ "男性" }}@elseif($user->profile->gender == 2){{ "女性" }}@endif @if($user->plan == 'free'){{ "フリー" }}@elseif($user->plan == 'lite'){{ "ライト" }}@elseif($user->plan == 'standard'){{ "スタンダード" }}@endif
            @endif
          @endauth
          @guest
          {{ $user->profile->pref ?? '' }} {{ $user->profile->age ?? '' }} @if($user->profile->gender == 1){{ "男性" }}@elseif($user->profile->gender == 2){{ "女性" }}@endif @if($user->plan == 'free'){{ "フリー" }}@elseif($user->plan == 'lite'){{ "ライト" }}@elseif($user->plan == 'standard'){{ "スタンダード" }}@endif
          @endguest
        </p>
      </h2>
      @auth
        @if( Auth::id() !== $user->id )
          @if( !($user->isBlockedBy(Auth::user()) ||  Auth::user()->isBlockedBy($user)))
            <follow-button
              class="ml-auto"
              :initial-is-followed-by='@json($user->isFollowedBy(Auth::user()))'
              :authorized='@json(Auth::check())'
              endpoint="{{ route('users.follow', ['name' => $user->name]) }}"
            >
            </follow-button> 
          @endif
        @endif
      @endauth

    </div>
    @if ($user->profile->gender !== null)
    @endif
  </div>
  <div class="card-body py-0">
    <div class="card-text">
      @auth
        @if( !($user->isBlockedBy(Auth::user()) ||  Auth::user()->isBlockedBy($user)))
          {!! nl2br(e($user->profile->profile)) !!}
        @endif
      @endauth
      @guest
        {!! nl2br(e($user->profile->profile)) !!}
      @endguest
    </div>
  </div>
  @auth
    @if( Auth::id() !== $user->id )
    @endif
  @endauth
  
  @auth
    @if( !($user->isBlockedBy(Auth::user()) ||  Auth::user()->isBlockedBy($user)))
      @if (($user->profile->canSendGender == 9 && $user->profile->gender == Auth::user()->profile->canSendGender) || (Auth::user()->profile->canSendGender == 9 && Auth::user()->profile->gender == $user->profile->canSendGender) || (($user->profile->gender == 2 && $user->profile->canSendGender == 9) && (Auth::user()->profile->gender == 1 && Auth::user()->profile->canSendGender == 9)) || ((Auth::user()->profile->gender == 2 && Auth::user()->profile->canSendGender == 9) && ($user->profile->gender == 1 && $user->profile->canSendGender == 9)) || ($user->profile->canSendGender == Auth::user()->profile->gender && $user->profile->gender == Auth::user()->profile->canSendGender) || (($user->profile->gender == 1 && $user->profile->canSendGender == 1) && (Auth::user()->profile->gender == 1 && Auth::user()->profile->canSendGender == 1)) || ($user->profile->canSendGender == 9 && ($user->profile->gender == Auth::user()->profile->gender) && ($user->profile->canSendGender == Auth::user()->profile->canSendGender)) || (($user->profile->gender == 2 && $user->profile->canSendGender == 1) && (Auth::user()->profile->gender == 1 && Auth::user()->profile->canSendGender == 2)) || (($user->profile->gender == 1 && $user->profile->canSendGender == 2) && (Auth::user()->profile->gender == 2 && Auth::user()->profile->canSendGender == 1)))
        @if ($user->profile->realname !== null && $user->profile->zipcode1 !== null && $user->profile->zipcode2 !== null && $user->profile->address1 !== null && $user->profile->address2 !== null)
        <div class="card-body d-flex flex-row pb-0">
          @if( Auth::id() !== $user->id )
            @if($user->profile->status == '1')
              <pre-qutte-reader
                from='{{ Auth::id() }}'
                to='{{ $user->id }}'
              >
              </pre-qutte-reader>
            @endif
          @endif
          <div class="m-0 card-text">
            <p class="mb-0">
              {{ $user->profile->status == 1 ? "募集中！" : "募集停止" }}
            </p>
            <p class="mb-0">
              @if ($user->profile->condition !== null)
                {{ $user->profile->condition == 1 ? "まったり派" : "テキパキ派" }}
              @endif
            </p>
          </div>
        </div>
        @endif
      @endif
    @endif
  @endauth

  @auth
    @if( !($user->isBlockedBy(Auth::user()) ||  Auth::user()->isBlockedBy($user)))
      @foreach($user->hobbies as $hobby)
        @if($loop->first)
          <div class="card-body pb-0 pl-3">
            <div class="card-text line-height">
        @endif
              <span style="display: inline-flex;">
                <a href="{{ route('hobbies.show', ['name' => $hobby->name]) }}" class="border p-1 mr-1 mt-1 text-muted">
                  {{ $hobby->hashtag }}
                </a>
              </span>
        @if($loop->last)
            </div>
          </div>
        @endif
      @endforeach
    @endif
  @endauth
  @guest
    @foreach($user->hobbies as $hobby)
      @if($loop->first)
        <div class="card-body pb-0 pl-3">
          <div class="card-text line-height">
      @endif
            <span style="display: inline-flex;">
              <a href="{{ route('hobbies.show', ['name' => $hobby->name]) }}" class="border p-1 mr-1 mt-1 text-muted">
                {{ $hobby->hashtag }}
              </a>
            </span>
      @if($loop->last)
          </div>
        </div>
      @endif
    @endforeach
  @endguest


  <div class="card-body pt-0 d-flex ">
    <div class="card-text">
      <a href="{{ route('users.followings', ['name' => $user->name]) }}" class="text-muted">
        {{ $user->count_followings }} フォロー
      </a>
      <a href="{{ route('users.followers', ['name' => $user->name]) }}" class="text-muted">
        {{ $user->count_followers }} フォロワー
      </a>
    </div>
      @auth
        @if( Auth::id() !== $user->id )
          @if(($user->isBlockedBy(Auth::user())) || (!$user->isBlockedBy(Auth::user())) && !Auth::user()->isBlockedBy($user))
            <block-button
              class="ml-auto"
              :initial-is-blocked-by='@json($user->isBlockedBy(Auth::user()))'
              :authorized='@json(Auth::check())'
              endpoint="{{ route('users.block', ['name' => $user->name]) }}"
            >
            </block-button>
          @endif
        @endif
      @endauth

    
  </div>
</div>
