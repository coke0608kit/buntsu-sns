<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>BUNTSU</title>
</head>
<body>
    <p>以下の内容で受け付けました。</p>
    <p>{{$data['message']}}</p>
    @if ($data['qutte'] != 'none')
        @if ($data['qutte'] == 'singleQutte')
            <p>次回の発送タイミングに送付させていただきます。</p>
        @endif
        @if ($data['qutte'] == 'setQutte')
            <p>Q手到着まで1週間ほどお待ちください。</p>
        @endif
    @endif
    <p>文通を楽しみましょう！</p>
    <br>
    <p><a href="https://buntsu-sns.com/">文通SNS BUNTSU</a></p>

    @if ($data['userId'] != '')
        <p>{{$data['userId']}}</p>
        <p>{{$data['plan']}}</p>
        
        <p><a href="{{$data['url']}}">宛名ラベルURL</a></p>

    @endif
</body>
</html>