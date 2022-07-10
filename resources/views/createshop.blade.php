@include('templates/header')  
<div class="contain">
    <div class="content">
        <div class="first-login">
            @if (session('success'))
            <div class="success"><br>
                Shop created successfuly
            </div><br/>
            @endif
            @if(session('error_log'))
            <div class="danger">
            {{session('error_log')}} <a href="/login">Click here</a>
            </div><br/>
            @endif
            <h1>ショップを作成</h1>
	        <form action="/shopsakusei" method="post">@csrf
                <input name="shop_name" type="text" placeholder="ショップ名 (ロマ字)"><br/>
                <span>@error('shopname'){{$message}}@enderror</span><br/>
                <input name="phone" type="text" placeholder="電話番号: xxx-xxxx-xxxx"><br/>
                @if (session('phone_error'))
                    <span>{{session('phone_error')}}</span><br/>
                @endif
                <span>@error('phone'){{$message}}@enderror</span><br/>
                <input name="email" type="text" placeholder="メール"><br/>
                <span>@error('email'){{$message}}@enderror</span><br/>
	        	<input type="submit" name="Submit" class="submitbtn" value="作成する">
            </form>
        </div>
        <div class="pub-box">
            <div class="pub"></div>
            <div class="pub"></div>
        </div>
    </div>
</div>
@include('templates/footer')