@include('templates/header')  
        <div class="contain">
            <div class="content">
                <div class="form">
                    <h1>会員になります。</h1>
                    @if(session('success'))
                    <span id="success_message">{{session('success')}}</span><br/>
                    <span id="success_message">Please check your Email to activate your account.</span><br/>
                    @endif
                    @if(session('confirm_error'))
                    <span style="color:red">{{session('confirm_error')}}</span><br/>
                    @endif
                    <form id="register_form" action="user_register" method="post">@csrf
                        @if (session('name_romaji_error'))
                        <span id="error">{{session('name_romaji_error')}}</span><br/>
                        @endif
                        <input id="first_name" name="first_name" placeholder="名前" type="text"/><br/>
                        <span id="error">@error('first_name'){{$message}}@enderror</span><br/>
                        <input id="last_name" name="last_name" placeholder="名前" type="text"/><br/>
                        <span id="error">@error('last_name'){{$message}}@enderror</span><br/>
                        <label for="birth_date">生年月日:</label><br/>
                        <input type="date" name="birth_date"/><br/>
                        <span id="error">@error('birth_date'){{$message}}@enderror</span><br/>
                        <input name="email" placeholder="Email" id="email" type="text"><br/>
                        <span id="email_result">@error('email'){{$message}}@enderror</span><br/>
                        <select name="gender">
                            <option value="Masculin">性別</option>
                            <option value="Masculin">男</option>
                            <option value="Feminin">女</option>
                        </select><br/>
                        <input name="password" placeholder="パスワード" id="password" type="password"><br/>
                        <span id="error">@error('password'){{$message}}@enderror</span><br/>
                        <input name="password_confirmation" placeholder="パスワード確認" id="c_password" type="password"><br/>
                        <span id="error">@error('password_confirmation'){{$message}}@enderror</span><br/>
                        <input class="checkbox" type="checkbox" onclick="show()"><label>パスワードを表示</label><br/>
                        <input id="register" type="submit" name="Submit" class="submitbtn" value="会員登録">
                    </form>
                </div>
                <div class="pub-box">
                    <div class="pub"></div>
                    <div class="pub"></div>
                </div>
            </div>
        </div>
@include('templates/footer')  