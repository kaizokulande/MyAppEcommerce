   
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('../css/mail.css') }}" rel="stylesheet" type="text/css" media="screen" />
    <link href="mail.css" rel="stylesheet" type="text/css" media="screen" />
    <title>パスワードを忘れたメール</title>
</head>
<body>
    <div class="m_content">
        <h2>パスワードを忘れたメール</h2>
        <div>
            <p>以下のリンクからパスワードをリセットできます。</p>
            <a href="http://127.0.0.1:8000/reset_password/{{$token}}">パスワードをリセット</a>
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