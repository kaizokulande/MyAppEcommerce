<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('../css/mail.css') }}" rel="stylesheet" type="text/css" media="screen" />
    <link href="mail.css" rel="stylesheet" type="text/css" media="screen" />
    <title>{{$subject}}</title>
</head>
<body>
    <div class="m_content">
        <p>{{$firstname}} {{$lastname}} 様</p>
        <div>
            <p>
                この度は KAIBAI の{{$subsname}}プランに登録、誠にありがとうございます。<br>
                今日から{{$limitdate}}まで{{$subsname}}プランへの機能とサービスが利用いただけます。<br>
            </p>
        </div>
        <div>
            <p>プランを変えたい時はいつでも変えられます。<br>
            </p>
        </div>
    </div>
    <div class="m_footer">
        <div class="logo"><a href="http://127.0.0.1:8000"><img src="{{ asset('../images/logo.png') }}"></a></div>
        <div class="m_contact">
            <p><a href="http://127.0.0.1:8000">kaibai.jp</a></p>
            <p>kaibaienterprise@kaibai.jp</p>
        </div>
    </div>
</body>
</html>
