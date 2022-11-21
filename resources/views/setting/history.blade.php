@extends('app')

@section('title', 'やりとり')

@section('content')
@section('breadcrumbs', Breadcrumbs::render('history'))
  <div class="container mb-5">
    <h4 class="my-4">【やりとり】</h4>
    <div class="accordion" id="accordion">
        @foreach($resultUserIds as $userId)
          <div class="card mb-3">
            <div id="heading_{{ $userId['id'] }}">
              <h5 class="mb-0">
                <button class="btn btn-link collapsed w-100" type="button" data-toggle="collapse" data-target="#collapse_{{ $userId['id'] }}" aria-expanded="false" aria-controls="collapse_{{ $userId['id'] }}">
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                      @if ($userId['icon'])
                        <img class="mr-2" style="width:45px;" alt="{{ $userId['name'] }}" src="{{ $userId['icon'] }}">
                      @else
                        <i class="fas fa-user-circle fa-3x mr-2"></i>
                      @endif
                      {{ mb_strimwidth($userId['nickname'], 0, 15, "...", 'UTF-8') }}
                    </div>
                    {{ $userId['latest']->format('Y/m/d H:i') }}
                  </div>
                </button>
              </h5>
            </div>
            <div id="collapse_{{ $userId['id'] }}" class="collapse" aria-labelledby="heading_{{ $userId['id'] }}" data-parent="#accordion">
              <div class="card-body">
                <ul class="list-group">
                @foreach($usersInfo as $userInfo)
                  @if ($userInfo['fromUser']->id == $userId['id'] || $userInfo['toUser']->id == $userId['id'])
                  <li class="list-group-item mb-3">
                    <div style="display: flex;justify-content: space-between;">
                    @if ($userInfo['status'] == 0) 
                      <p style="margin-bottom: 0;">設定日時  </p>
                    @else
                      <p style="margin-bottom: 0;">受付日時  </p>
                    @endif
                      <p style="margin-bottom: 0;">{{ $userInfo["ticket"]["updated_at"]->format('Y/m/d H:i') }}</p>
                    </div>
                    <hr>
                    <div class="row">
                      <a href="{{ route('users.show', ['name' => $userInfo['fromUser']->name]) }}" class="text-nowrap text-dark col text-center" style='display: grid;justify-content: center;justify-items: center;'>
                        @if ($userInfo["fromUser"]->profile->icon)
                          <img style="width:45px;" alt="{{ $userInfo['fromUser']->name }}" src="{{ $userInfo['fromUser']->profile->icon }}">
                        @else
                          <i class="fas fa-user-circle fa-3x"></i>
                        @endif
                        {{ mb_strimwidth($userInfo["fromUser"]->nickname, 0, 12, "...", 'UTF-8') }}
                      </a>
                      <div class="col text-center">
                        <i class="fa-solid fa-arrow-right fa-3x"></i>
                      </div>
                      <a href="{{ route('users.show', ['name' => $userInfo['toUser']->name]) }}" class="text-nowrap text-dark col text-center" style='display: grid;justify-content: center;justify-items: center;'>
                        @if ($userInfo["toUser"]->profile->icon)
                          <img style="width:45px;" alt="{{ $userInfo['toUser']->name }}" src="{{ $userInfo['toUser']->profile->icon }}">
                        @else
                          <i class="fas fa-user-circle fa-3x"></i>
                        @endif
                        {{ mb_strimwidth($userInfo["toUser"]->nickname, 0, 12, "...", 'UTF-8') }}
                      </a>
                    </div>
                  </li>
                  @endif
                @endforeach
                </ul>
              </div>
            </div>
          </div>
        @endforeach
    </div>
  </div>
@endsection