@extends('app')

@section('title', 'ブロックリスト')

@section('content')
@section('breadcrumbs', Breadcrumbs::render('block'))
  <div class="container mb-5">
    <h4 class="my-4">【ブロックリスト】</h4>
    <ul class="list-group">
      @foreach($user->blockings as $blockUser)
        <li class="list-group-item">
          <div class="d-flex flex-row align-items-center">
            <a href="{{ route('users.show', ['name' => $blockUser->name]) }}" class="text-dark">
              @if ($blockUser->profile->icon)
                <img class="mr-2" style="width: 45px;" src="{{ $blockUser->profile->icon }}" alt="{{ $blockUser->name }}">
              @else
                <i class="fas fa-user-circle fa-3x mr-1"></i>
              @endif
            </a>
            <div>
              <div class="font-weight-bold">
                <a href="{{ route('users.show', ['name' => $blockUser->name]) }}" class="text-dark">
                  {{ $blockUser->nickname }}
                </a>
              </div>
            </div>
            @if(($user->isBlockedBy(Auth::user())) || (!$user->isBlockedBy(Auth::user())) AND !Auth::user()->isBlockedBy($user))
              <block-button
                class="ml-auto"
                :initial-is-blocked-by='@json($blockUser->isBlockedBy(Auth::user()))'
                :authorized='@json(Auth::check())'
                endpoint="{{ route('users.block', ['name' => $blockUser->name]) }}"
              >
              </block-button>
            @endif
          </div>
        </li>
      @endforeach
    </ul>
  </div>
@endsection