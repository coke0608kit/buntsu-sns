<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>
    文通SNS BUNTSU | @yield('title')
  </title>
  <link rel="shortcut icon" href="{{ asset('images/BUNTSU.ico') }}">
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png.png') }}">
  <link rel="icon" sizes="192x192" href="{{ asset('images/android-touch-icon.png') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
  <!-- Bootstrap core CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
  <!-- Material Design Bootstrap -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.11/css/mdb.min.css" rel="stylesheet">
  <style>
      .container {
        max-width: 540px;
      }
  </style>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script>
  var ua = navigator.userAgent.toLowerCase();
  var isiOS = (ua.indexOf('iphone') > -1) || (ua.indexOf('ipad') > -1);
  if(isiOS) {
    var viewport = document.querySelector('meta[name="viewport"]');
    if(viewport) {
      var viewportContent = viewport.getAttribute('content');
      viewport.setAttribute('content', viewportContent + ', user-scalable=no');
    }
  }
  </script>
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-PTDGC220SQ"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-PTDGC220SQ');
  </script>
</head>

<body>
  <div id="app" style="padding-bottom: 70px;">
    @include('nav')
    @yield('breadcrumbs')
    @yield('content')
    @include('footer')
  </div>

  <script src="{{ mix('js/app.js') }}"></script> {{--この行を追加--}}
  <!-- JQuery -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.11/js/mdb.min.js"></script>
</body>

</html>
