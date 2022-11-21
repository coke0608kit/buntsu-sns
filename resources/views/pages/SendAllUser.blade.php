<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$data['title']}}</title>
</head>
<body>
    <p>BUNTSUからのお知らせです。</p>
    <p>{!!nl2br(htmlspecialchars($data['body']))!!}</p>
    
    <p>文通を楽しみましょう！<br><a href="https://buntsu-sns.com/">文通SNS BUNTSU</a></p>
</body>
</html>