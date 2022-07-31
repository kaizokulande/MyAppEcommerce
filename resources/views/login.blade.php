@include('templates/header')
    </header>
        <div class="contain">
            <div class="content">
                <div class="first-login">
                    <h1>ログイン</h1>
                    @error('error_conf_mail')
                        <span style="color:red">{{$message}}</span>
                    @enderror
                    @if(session('login_error'))
                        <span style="color:red">{{session('login_error')}}</span>
                    @endif
                    @if (session('message'))
                    <span style="color:green">{{session('message')}}</span>
                    @endif
                    <form action="login" method="post">@csrf
                        <input name="email" placeholder="メール" type="text"><br/>
                        <span>@error('email'){{$message}}@enderror</span><br/>
                        <input name="password" placeholder="パスワード" id="password" type="password"><br/>
                        @error('password')<span>{{$message}}</span><br/>@enderror
                        <span>@error('error_pass'){{$message}}@enderror</span><br/>
                        <input class="checkbox" type="checkbox" onclick="show_password()"><label>パスワードを表示</label><br/>
                        <input type="submit" name="Submit" class="submitbtn" value="ログイン">
                    </form>
                    <a href="{{ route('forget.password.get') }}">パスワードを忘れました</a>
                </div>
                <div class="pub-box">
                    <div class="pub"></div>
                    <div class="pub"></div>
                </div>
            </div>
        </div>
@include('templates/footer')