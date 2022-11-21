@if($article->user->official == 1)  
<div class="card mt-3 " style='background: #FFF2CC;'>
@else
<div class="card mt-3" >
@endif
  <div class="card-body d-flex flex-row">
    <a href="{{ route('users.show', ['name' => $article->user->name]) }}" class="text-dark mr-2">
      @if ($article->user->profile->icon)
        <img style="width: 45px;" src="{{ $article->user->profile->icon }}" alt="">
      @else
        <i class="fas fa-user-circle fa-3x mr-1"></i>
      @endif
    </a>
    <div>
      <div class="font-weight-bold">
        <a href="{{ route('users.show', ['name' => $article->user->name]) }}" class="text-dark">
          {{ $article->user->nickname }}
        </a>
      </div>
      <div class="font-weight-lighter">
        {{ $article->created_at->format('Y/m/d H:i') }}
      </div>
    </div>
  @if( Auth::id() === $article->user_id )
    <!-- dropdown -->
      <div class="ml-auto card-text">
        <div class="dropdown">
          <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <button type="button" class="btn btn-link text-muted m-0 p-2">
              <i class="fas fa-ellipsis-v"></i>
            </button>
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="{{ route("articles.edit", ['article' => $article]) }}">
              <i class="fas fa-pen mr-1"></i>投稿を更新する
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item text-danger" data-toggle="modal" data-target="#modal-delete-{{ $article->id }}">
              <i class="fas fa-trash-alt mr-1"></i>投稿を削除する
            </a>
          </div>
        </div>
      </div>
      <!-- dropdown -->

      <!-- modal -->
      <div id="modal-delete-{{ $article->id }}" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="POST" action="{{ route('articles.destroy', ['article' => $article]) }}">
              @csrf
              @method('DELETE')
              <div class="modal-body">
                削除します。よろしいですか？
              </div>
              <div class="modal-footer justify-content-between">
                <a class="btn btn-outline-grey" data-dismiss="modal">キャンセル</a>
                <button type="submit" class="btn btn-danger">削除する</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- modal -->
  @else
      <follow-button
        class="ml-auto"
        :initial-is-followed-by='@json($article->user->isFollowedBy(Auth::user()))'
        :authorized='@json(Auth::check())'
        endpoint="{{ route('users.follow', ['name' => $article->user->name]) }}"
      >
      </follow-button>
  @endif

  </div>
  <div class="card-body pt-0 pb-2">
    <div class="card-text">
        <img style="width:100%;" alt="{{ $article->image }}" src="{{ $article->image }}">
    </div>
  </div>
  <div class="card-body pt-0 pb-2 pl-3">
    <div class="card-text">
      <article-like
        :initial-is-liked-by='@json($article->isLikedBy(Auth::user()))'
        :initial-count-likes='@json($article->count_likes)'
        :authorized='@json(Auth::check())'
        endpoint="{{ route('articles.like', ['article' => $article]) }}"
      >
      </article-like>
    </div>
  </div>
  @foreach($article->tags as $tag)
    @if($loop->first)
      <div class="card-body pt-0 pb-4 pl-3">
        <div class="card-text line-height">
    @endif
          <span style="display: inline-flex;">
            <a href="{{ route('tags.show', ['name' => $tag->name]) }}" class="border p-1 mr-1 mt-1 text-muted">
              {{ $tag->hashtag }}
            </a>
          </span>
    @if($loop->last)
        </div>
      </div>
    @endif
  @endforeach
</div>
<div class="mt-5 mb-10">
  <ul class="list-group">
    @foreach($article->likes as $likeUser)
      @if($loop->first)
      <p>いいねしたユーザー</p>
      @endif
      <li class="list-group-item">
        <div class="d-flex flex-row align-items-center">
          <a href="{{ route('users.show', ['name' => $likeUser->name]) }}" class="text-dark">
            @if ($likeUser->profile->icon)
              <img class="mr-2" style="width: 45px;" src="{{ $likeUser->profile->icon }}" alt="{{ $likeUser->name }}">
            @else
              <i class="fas fa-user-circle fa-3x mr-1"></i>
            @endif
          </a>
          <div>
            <div class="font-weight-bold">
              <a href="{{ route('users.show', ['name' => $likeUser->name]) }}" class="text-dark">
                {{ $likeUser->nickname }}
              </a>
            </div>
            <div class="card-text line-height" style="font-size: 12px;">
              @auth
                @if( !($likeUser->isBlockedBy(Auth::user()) ||  Auth::user()->isBlockedBy($likeUser)))
                  {{ $likeUser->profile->status == 1 ? "募集中！" : "募集停止" }}
                  {{ $likeUser->profile->pref ?? '' }} @if($likeUser->profile->year ?? ''){{ floor((date('Ymd') - sprintf('%04d%02d%02d', $likeUser->profile->year, $likeUser->profile->month, $likeUser->profile->day)) / 100000) * 10 . "代" }}@endif @if($likeUser->profile->gender == 1){{ "男性" }}@elseif($likeUser->profile->gender == 2){{ "女性" }}@endif  @if($likeUser->plan == 'free'){{ "フリー" }}@elseif($likeUser->plan == 'lite'){{ "ライト" }}@elseif($likeUser->plan == 'standard'){{ "スタンダード" }}@endif
                @endif
              @endauth
              @guest
                {{ $likeUser->profile->status == 1 ? "募集中！" : "募集停止" }}
                {{ $likeUser->profile->pref ?? '' }} @if($likeUser->profile->year ?? ''){{ floor((date('Ymd') - sprintf('%04d%02d%02d', $likeUser->profile->year, $likeUser->profile->month, $likeUser->profile->day)) / 100000) * 10 . "代" }}@endif @if($likeUser->profile->gender == 1){{ "男性" }}@elseif($likeUser->profile->gender == 2){{ "女性" }}@endif  @if($likeUser->plan == 'free'){{ "フリー" }}@elseif($likeUser->plan == 'lite'){{ "ライト" }}@elseif($likeUser->plan == 'standard'){{ "スタンダード" }}@endif
              @endguest
            </div>
          </div>
          @if( Auth::id() !== $likeUser->id )
            <follow-button
              class="ml-auto"
              :initial-is-followed-by='@json($likeUser->isFollowedBy(Auth::user()))'
              :authorized='@json(Auth::check())'
              endpoint="{{ route('users.follow', ['name' => $likeUser->name]) }}"
            >
            </follow-button>
          @endif
        </div>
      </li>
    @endforeach
  </ul>
</div>