@include('templates/header')
    </header>
        <div class="contain">
            <div class="content">
                <div class="first-login">
                    <h1>Login</h1>
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
                        <input name="email" placeholder="Email" type="text"><br/>
                        <span>@error('email'){{$message}}@enderror</span><br/>
                        <input name="password" placeholder="Mot de passe" id="password" type="password"><br/>
                        @error('password')<span>{{$message}}</span><br/>@enderror
                        <span>@error('error_pass'){{$message}}@enderror</span><br/>
                        <input class="checkbox" type="checkbox" onclick="show_password()"><label>Afficher le mot de passe</label><br/>
                        <input type="submit" name="Submit" class="submitbtn" value="Login">
                    </form>
                    <a href="{{ route('forget.password.get') }}">Mot de passe oublié?</a>
                </div>
                <div class="pub-box">
                    <div class="pub"><img src="{{ asset('../images/pub_ecom.jpg') }}"/></div>
                    <div class="pub"><img src="{{ asset('../images/ecom.png') }}"/></div>
                </div>
            </div>
        </div>
@include('templates/footer')