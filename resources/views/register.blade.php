@include('templates/header')  
        <div class="contain">
            <div class="content">
                <div class="form">
                    <h1>Inscription</h1>
                    @if(session('success'))
                    <span id="success_message">{{session('success')}}</span><br/>
                    <span id="success_message">Verifiez votre Email pour activer votre compte.</span><br/>
                    @endif
                    @if(session('confirm_error'))
                    <span style="color:red">{{session('confirm_error')}}</span><br/>
                    @endif
                    <form id="register_form" action="user_register" method="post">@csrf
                        @if (session('name_romaji_error'))
                        <span id="error">{{session('name_romaji_error')}}</span><br/>
                        @endif
                        <input id="first_name" name="first_name" placeholder="Nom" type="text"/><br/>
                        <span id="error">@error('first_name'){{$message}}@enderror</span><br/>
                        <input id="last_name" name="last_name" placeholder="Prénom" type="text"/><br/>
                        <span id="error">@error('last_name'){{$message}}@enderror</span><br/>
                        <label for="birth_date">Date de naissance:</label><br/>
                        <input type="date" name="birth_date"/><br/>
                        <span id="error">@error('birth_date'){{$message}}@enderror</span><br/>
                        <input name="email" placeholder="Email" id="email" type="text"><br/>
                        <span id="email_result">@error('email'){{$message}}@enderror</span><br/>
                        <select name="gender">
                            <option value="Masculin">Genre</option>
                            <option value="Masculin">Homme</option>
                            <option value="Feminin">Femme</option>
                        </select><br/>
                        <input name="password" placeholder="Mot de passe" id="password" type="password"><br/>
                        <span id="error">@error('password'){{$message}}@enderror</span><br/>
                        <input name="password_confirmation" placeholder="Confirmer votre mot de passe" id="c_password" type="password"><br/>
                        <span id="error">@error('password_confirmation'){{$message}}@enderror</span><br/>
                        <input class="checkbox" type="checkbox" onclick="show()"><label>Afficher le mot de passe</label><br/>
                        <input id="register" type="submit" name="Submit" class="submitbtn" value="S'inscrire">
                    </form>
                </div>
                <div class="pub-box">
                    <div class="pub"><img src="{{ asset('../images/pub_ecom.jpg') }}"/></div>
                    <div class="pub"><img src="{{ asset('../images/ecom.png') }}"/></div>
                </div>
            </div>
        </div>
@include('templates/footer')  