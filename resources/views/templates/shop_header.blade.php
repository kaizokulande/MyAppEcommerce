<?php
	use Illuminate\Support\Str;
	$user = Auth::user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('../css/style.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('../css/media.css') }}" rel="stylesheet" type="text/css" media="screen" />
    <link href="{{ asset('../css/page.css') }}" rel="stylesheet" type="text/css" media="screen" />
    <link href="{{ asset('../css/shop.css') }}" rel="stylesheet" type="text/css" media="screen" />
    <link rel="stylesheet" type="text/css"  href="{{ asset('../font/css/fontawesome.min.css') }}">
    <link rel="stylesheet" type="text/css"  href="{{ asset('../font/css/all.min.css') }}">
    <script type="text/javascript" src="{{ asset('../js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('../js/script.js') }}"></script>
    <script type="text/javascript" src="{{ asset('../js/page.js') }}"></script>
</head>

<body>
	<header id="shop-header"><!--header-->
		<div class="header-middle"><!--header-middle-->
			<div class="container">
				<div class="logo">
					<a href="/{{$shop->shop_name}}"><span style="color: #111; font-family: 'Helvetica Neue', sans-serif; font-size: 27px; font-weight: bold; letter-spacing: -1px; line-height: 1; text-align: center;"><span id="hd_name" style="color: rgba(0, 124, 163, 1)">{{$shop->shop_name}}</span></span></a>
				</div>
				<div class="shop-menu">
					<ul>
						<li class="drop-menu-cart">
							<div class="navbar-button">
								<div id="in-cart-shop">
									@if (Cart::Count()>0)
									<div class="cart-shop-number"><span>{{Cart::count()}}</span></div>
									@endif
								</div>
								<a href="/shoppingcart"><i><img src="{{ asset('../images/icon/833314.png') }}"></i><i>カート</i></a>
								@if (Cart::Count()>0)
								<div class="sub-menu-cart">
									<a href="/delete_all_cart">カートを空にする</a>
								</div>
								@endif
							</div>
						</li>
						<li class="drop-menu-user">
							<a href="#">
								@if (Auth::check())
									<span class="online-stat"></span>
									<img src="{{ asset($user->logo_type) }}" /><span class="user-name">{{$user->firstname}} {{ $user->lastname }}</span>
								@else
									<i><img src="{{ asset('../images/icon/1946429.png') }}"></i><i>マイアカウント</i>
								@endif
							</a>
							<ul class="sub-menu-user">
								@if (Auth::check())
									<li><a href="/sellarticle">セールする</a></li>
									<li><a href="/stock">ダッシュボード</a></li>
									<li><a href="/u/logout">ログアウト</a></li>
								@else
									<li><a href="/register">会員登録</a></li>
									<li><a href="/login">ログイン</a></li>
								@endif
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div><!--/header-middle-->
	
		<div class="header-bottom">
			<div class="container">
					<div class="navigation">
						<div class="mainmenu">
							<ul>
								<li><a href="/" class="active">Kaibaiに戻る</a></li>
								<li><a href="/{{$shop->shop_name}}" class="active">ホーム</a></li>
								@can('isAdmin')
								<li class="drop-menu"><a href="#">ショップ<i class="fa fa-angle-down"></i></a>
                                    <ul role="menu" class="sub-menu">
                                        <li><a href="/{{$shop->shop_name}}/addarticle">商品を加える</a></li>
                                        <li><a href="/{{$shop->shop_name}}/stock">ストック</a></li>
										<li><a href="/{{$shop->shop_name}}/shop_solded">売られた商品</a></li>
										<li><a href="/{{$shop->shop_name}}/setting">設定</a></li>
                                    </ul>
                                </li>
								@endcan
								<li><a href="contact-us.html">コンタクト</a></li>
							</ul>
						</div>
					</div>
					<div class="shop-search">
						<div class="search_box">
							<form action="/shop/{{$shop->shop_name}}/articles" method="get">
								<input type="text" name="search" placeholder="検索..."/>
								<button type="submit" class="search_box" value="search"><i class="fas fa-search" aria-hidden="true"></i></button>
							</form>
						</div>
					</div>
			</div>
		</div>
	</header>
    <div class="cart-error">
        <div class="error-content">
            <span class="close-error">&times;</span>
            <div class="error-message">
                <p id="error-cart"></p>
            </div>
        </div>
    </div>
	<div class="body-container">
        <div class="left-sidebar">
			
			<div class="shop-info">
				<h2>ショップ情報</h2>
				<p><span id="nav_phone">{{$shop->phone_number}}</span></p>
				<p><span id="nav_email">{{Str::limit($shop->shop_email,32,'...')}}</span><p>
				<p><span><a id="nav_site" href="{{$shop->shop_site}}">{{Str::limit($shop->shop_site,32,'...')}}</a></span><p>
			</div>
			<div class="line"></div>
			<!-- admin -->
			@can('isAdmin')
			<div class="admins-section">
				<h2>管理者</h2>
					<div class="admins">
						<div class="administrator">
							<div class="admin-image">
								<div class="online-status"></div>
								<img src="{{ asset($user->logo_type) }}" />
							</div>
							<div class="admin-name">{{ $user->firstname }} {{ $user->lastname }}</div>
						</div>
					</div>
					<div class="line"></div>
						@foreach ($admins as $adm)
						<div class="admins">
							<div class="administrator">
								<div class="admin-image">
									@if (Cache::has('is_online'. $adm->id))
										<div class="online-status"></div>
									@else
										<div class="lastsen"><span>{{ Carbon\Carbon::parse($adm->last_seen)->diffForHumans() }}</span></div>
                					@endif
									<img src="{{ asset($adm->logo_type) }}" />
								</div>
								<div class="admin-name">{{ $adm->firstname }} {{ $adm->lastname }}</div>
							</div>
						</div>
						@endforeach
					<!-- /admin -->
				</div>
			@endcan
			<div class="shop-categorie">
				<h2>カテゴリー</h2>
				@foreach ($categories as $cat)
				<ul class="cat_list">
                	<li><a href="/shop/{{$shop->shop_name}}/categorie/{{$cat->categorie}}">{{$cat->categorie}}</a></li>
				</ul>
                @endforeach
			</div>
        </div>