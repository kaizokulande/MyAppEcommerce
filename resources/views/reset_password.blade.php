@include('templates/header')
    </header>
        <div class="contain">
            <div class="content">
                <div class="first-login">
                    <h1>Réinitialiser le mot de passe</h1>
                    @error('error_conf_mail')
                        <span style="color:red">{{$message}}</span>
                    @enderror
                    <form action="{{ route('reset.password.post') }}" method="post">@csrf
                        <input name="email" placeholder="Email" type="text"><br/>
                        <span>@error('email'){{$message}}@enderror</span><br/>
                        <input name="password" placeholder="Mot de passe" id="password" type="password"><br/>
                        <span>@error('password'){{$message}}@enderror</span><br/>
                        <input name="password_confirmation" placeholder="Confirmer votre mot de passe" id="c_password" type="password"><br/>
                        @error('password_confirmation')<span>{{$message}}</span><br/>@enderror
                        <span>@error('error_pass'){{$message}}@enderror</span><br/>
                        <input class="checkbox" type="checkbox" onclick="show()"><label>Afficher le mot de passe</label><br/>
                        <input type="submit" name="Submit" class="submitbtn" value="Réinitialiser">
                    </form>
                </div>
                <div class="pub-box">
                    <div class="pub"><img src="{{ asset('../images/pub_ecom.jpg') }}"/></div>
                    <div class="pub"><img src="{{ asset('../images/ecom.png') }}"/></div>
                </div>
            </div>
        </div>
@include('templates/footer')