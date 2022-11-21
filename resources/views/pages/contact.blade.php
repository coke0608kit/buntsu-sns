@extends('app')

@section('title', 'お問い合わせ')

@section('content')
@section('breadcrumbs', Breadcrumbs::render('contact'))
  <div class="container mb-5">
    @foreach ($errors->all() as $error)
    <div class="alert alert-danger">
        {{$error}}
    </div>
    @endforeach
    
    @if (session('success'))
    <div class="alert alert-success">
        {{session('success')}}
    </div>
    @endif
    
    @if (session('error'))
    <div class="alert alert-danger">
        {{session('error')}}
    </div>
    @endif
    <h4 class="my-4">お問い合わせ</h4>
    <form action="{{route('contactSend')}}" method="post">
        @csrf
        <div class="form-group">
            <label for="uname">お名前</label>
            <input type="text" name="uname" id="uname" class="form-control" value="{{old('uname')}}" placeholder="お名前を入力してください">
        </div>
        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="text" name="email" id="email" class="form-control" value="{{old('email')}}" placeholder="メールアドレスを入力してください">
        </div>
        <div class="form-group">
            <label for="body">内容</label>
            <textarea name="body" id="body" class="form-control" rows="5" placeholder="内容を入力してください">{{old('body')}}</textarea>
        </div>
        <input style="display:none" type="text" name="honeypot" value="" >
        <input type="submit" value="問い合わせ" class="btn btn-primary">
    </form>
  </div>
@endsection