@include('templates/header')
    </header>
        <div class="contain">
            <div class="content">
                <div class="first-login">
                    <h1>パスワードのリンクを送</h1>
                    @if ($errors->has('email'))
                        <span>{{ $errors->first('email') }}</span>
                    @endif
                    @if (Session::has('message'))
                        <span style="color:green;">{{ Session::get('message') }}</span>
                    @endif
                    <form action="{{ route('forget.password.post') }}" method="post">@csrf
                        <input name="email" placeholder="メール" type="text"><br/>
                        <input type="submit" name="Submit" class="submitbtn" value="リンクを送る">
                    </form>
                </div>
                <div class="pub-box">
                    <div class="pub"></div>
                    <div class="pub"></div>
                </div>
            </div>
        </div>
@include('templates/footer')