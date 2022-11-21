<li class="list-group-item">
  <div class="d-flex flex-row align-items-center">
    <a href="{{ route('users.show', ['name' => $person->name]) }}" class="text-dark">
      @if ($person->profile->icon)
        <img class="mr-2" style="width: 45px;" src="{{ $person->profile->icon }}" alt="{{ $person->name }}">
      @else
        <i class="fas fa-user-circle fa-3x mr-1"></i>
      @endif
    </a>
    <div>
      <div class="font-weight-bold">
        <a href="{{ route('users.show', ['name' => $person->name]) }}" class="text-dark">
          {{ $person->nickname }}
        </a>
      </div>
      <div class="card-text line-height" style="font-size: 12px;">
          {{ $person->profile->status == 1 ? "募集中！" : "募集停止" }}
          {{ $person->profile->pref ?? '' }} @if($person->profile->year ?? ''){{ floor((date('Ymd') - sprintf('%04d%02d%02d', $person->profile->year, $person->profile->month, $person->profile->day)) / 100000) * 10 . "代" }}@endif @if($person->profile->gender == 1){{ "男性" }}@elseif($person->profile->gender == 2){{ "女性" }}@endif
      </div>
    </div>
    @if( Auth::id() !== $person->id )
      <follow-button
        class="ml-auto"
        :initial-is-followed-by='@json($person->isFollowedBy(Auth::user()))'
        :authorized='@json(Auth::check())'
        endpoint="{{ route('users.follow', ['name' => $person->name]) }}"
      >
      </follow-button>
    @endif
  </div>
</li>