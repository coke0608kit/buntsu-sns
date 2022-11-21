<nav class="d-flex navbar fixed-bottom navbar-expand-md navbar-light bg-white border-top px-0">
  <div class="position-relative text-center justify-content-center py-1" style="flex: 1;">
  @auth
    <a class="nav-item nav-link stretched-link p-0" href="{{ route('articles.index') }}" style="color: black;">
      <i class="fa-solid fa-house-chimney-user"></i>
    </a>
  @endauth
  @guest
    <a class="nav-item nav-link stretched-link p-0" href="{{ route('articles.all') }}" style="color: black;">
      <i class="fa-solid fa-house-chimney-user"></i>
    </a>
  @endguest
    <p class="m-0" style="font-size: 0.7em;">HOME</p>
  </div>
  <div class="position-relative text-center justify-content-center py-1" style="flex: 1;">
    <a class="nav-item nav-link stretched-link p-0" href="{{ route('search.article') }}" style="color: black;">
      <i class="fa-solid fa-magnifying-glass"></i>
    </a>
    <p class="m-0" style="font-size: 0.7em;">検索</p>
  </div>
  <div class="position-relative text-center py-1" style="flex: 1;">
  @auth
    <a class="nav-item nav-link stretched-link p-0" href="{{ route("setting.history") }}" style="color: black;">
      <i class="fa-solid fa-comments"></i>
    </a>
    <p class="m-0" style="font-size: 0.7em;">やりとり</p>
  @endauth
  @guest
    <a class="nav-item nav-link stretched-link p-0" href="{{ route("contact") }}" style="color: black;">
      <i class="fa-solid fa-envelope"></i>
    </a>
    <p class="m-0" style="font-size: 0.7em;">お問い合わせ</p>
  @endguest
  </div>
  <div class="position-relative text-center py-1" style="flex: 1;">
  @auth
    <a class="nav-item nav-link stretched-link p-0" href="{{ route("setting.index") }}" style="color: black;">
      <i class="fa-solid fa-gear"></i>
    </a>
    <p class="m-0" style="font-size: 0.7em;">設定</p>
  @endauth
  @guest
    <a class="nav-item nav-link stretched-link p-0" href="{{route('login')}}" style="color: black;">
      <i class="fa-solid fa-right-to-bracket"></i>
    </a>
    <p class="m-0" style="font-size: 0.7em;">ログイン</p>
  @endguest
  </div>
</nav>