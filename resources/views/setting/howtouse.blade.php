@extends('app')

@section('title', 'トップページ')

@section('content')
  <div class="container mb-5" style="padding: 0">
    <img style="width: 100%;" src="{{ asset('images/howto.jpg')}}">
  </div>
  @endsection

<style scoped>
#app {
    padding-bottom: 70px;
}
.container {
    max-width: 1024px !important;
}
</style>