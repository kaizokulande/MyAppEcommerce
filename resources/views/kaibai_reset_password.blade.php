@include('templates/header')
    </header>
        <div class="contain">
            <div class="content">
                <div class="first-login">
                    <h1>パスワードをリセット</h1>
                    @error('error_conf_mail')
                        <span style="color:red">{{$message}}</span>
                    @enderror
                    <form action="{{ route('reset.password.post') }}" method="post">@csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <input name="email" placeholder="メール" type="text"><br/>
                        <span>@error('email'){{$message}}@enderror</span><br/>
                        <input name="password" placeholder="パスワード" id="password" type="password"><br/>
                        <span>@error('password'){{$message}}@enderror</span><br/>
                        <input name="password_confirmation" placeholder="パスワード確認" id="c_password" type="password"><br/>
                        @error('password_confirmation')<span>{{$message}}</span><br/>@enderror
                        <span>@error('error_pass'){{$message}}@enderror</span><br/>
                        <input class="checkbox" type="checkbox" onclick="show()"><label>パスワードを表示</label><br/>
                        <input type="submit" name="Submit" class="submitbtn" value="パスワードをリセット">
                    </form>
                </div>
                <div class="pub-box">
                    <div class="pub"></div>
                    <div class="pub"></div>
                </div>
            </div>
        </div>
@include('templates/footer')