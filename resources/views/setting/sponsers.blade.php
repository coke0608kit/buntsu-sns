@extends('app')

@section('title', '設定')

@section('content')
@section('breadcrumbs', Breadcrumbs::render('sponser'))
  <div class="container mb-5">
    <h4 class="my-4">スポンサー</h4>
    <div style="text-align: center;">
      @foreach($sponsersA as $sponserA)
        <a href='{{ $sponserA->url }}' class="w-100" target="_blank">
          @if ($sponserA->image != '')
            <img class="w-100" style="margin-bottom: 5rem;" src="{{ asset('uploads/'.$sponserA->image)}}">
          @else
            <p class="w-100" style="color:black;margin-bottom: 5rem;text-align: center;font-size: 2.5rem;overflow-wrap: break-word;">{{ $sponserA->name }}</p>
          @endif
        </a>
      @endforeach
      @foreach($sponsersB as $sponserB)
        @if ($loop->first)
          <div style="display:flex;align-items: center;flex-wrap: wrap;justify-content: space-evenly;">
        @endif
            <a href='{{ $sponserB->url }}' style="width: 45%" target="_blank">
              <p class="w-100" style="color:black;margin-bottom: 5rem;text-align: center;font-size: 1.5rem;overflow-wrap: break-word;">{{ $sponserB->name }}</p>
            </a>
        @if ($loop->last)
          </div>
        @endif
      @endforeach
      @foreach($sponsersC as $sponserC)
        @if ($loop->first)
          <div style="display:flex;align-items: center;flex-wrap: wrap;justify-content: space-evenly;">
        @endif
          <p style="width: 30%;color:black;margin-bottom: 5rem;text-align: center;font-size: 1rem;overflow-wrap: break-word;">{{ $sponserC->name }}</p>
        @if ($loop->last)
          </div>
        @endif
      @endforeach
    </div>
    <div>
      <p>皆様のご支援によりBUNTSUを立ち上げることができました。まことに感謝しております。</p>
      <p style="text-align: right">BUNTSU 道下真人</p>
    </div>
  </div>
@endsection

