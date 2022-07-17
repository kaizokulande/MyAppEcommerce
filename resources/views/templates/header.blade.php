@inject('cart', 'Gloudemans\Shoppingcart\Facades\Cart')
@inject('shop_cont', 'App\Http\Controllers\Shop_controller')
@inject('bs_cont', 'App\Http\Controllers\Base_controller')
@php
    $shops = $shop_cont::getshops(Auth::id());
    $categories = $bs_cont::getCategories();
    $user = Auth::user();
@endphp
<!DOCTYPE html>
<html lang="ja">
<head>
<title>Kaibai</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('../css/style.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('../css/media.css') }}" rel="stylesheet" type="text/css" media="screen" />
    <link href="{{ asset('../css/page.css') }}" rel="stylesheet" type="text/css" media="screen" />
    <link rel="stylesheet" type="text/css"  href="{{ asset('../font/css/fontawesome.min.css') }}">
    <link rel="stylesheet" type="text/css"  href="{{ asset('../font/css/all.min.css') }}">
    <script type="text/javascript" src="{{ asset('../js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('../js/script.js') }}"></script>
    <script type="text/javascript" src="{{ asset('../js/page.js') }}"></script>
</head>
<body>
    <header>
        <div class="nav">
            <!-- <div class="bar"><i class="icon fa fa-bars fa-fw fa-lg" aria-hidden="true"></i></div> -->
            <a class="logo" href="/"><img class="logo-image" src="{{ asset('../images/logo.png') }}"/></a></span>
            <form class="search" action="/articles" method="get">
                <input name="search" placeholder="検索" type="text">
                <button type="submit" class="submitbtn"><i class="fas fa-search" aria-hidden="true"></i></button>
            </form>

        </div><!-- end header -->
        <div class="navbar">
            <div class="wrap-buttons">
                <div class="navbar-button m-b-header"><a href="/"><i><img src="{{ asset('../images/icon/2631151.png') }}"></i><i>ホーム</i></a></div>
                <div class="navbar-button">
                    <a href="#"><i><img src="{{ asset('../images/icon/1141964.png') }}"></i><i>カテゴリー</i></a>
                    <div class="dropdown">
                        @foreach ($categories as $cat)
                            <a href="/cat/{{$cat->categorie}}">{{$cat->categorie}}</a>
                        @endforeach
                    </div>
                </div>
                <div class="navbar-button">
                    <a href="#"><i><img src="{{ asset('/images/icon/287623.png') }}"></i><i>ショップ</i></a>
                    <div class="dropdown">
                        @if (Auth::check())
                            @foreach ($shops as $s )
                            <a href="/{{$s->shop_name}}">{{$s->shop_name}}</a>
                            @endforeach
                        @endif
                        @if (!Auth::check())
                            <a href="/plans">プランを見る</a>
                            <a href="/createshop">ショップ作成</a>
                        @endif
                        @can('isNotSubscribed')
                            @can('shopnotExist')
                                <a href="/createshop">ショップ作成</a>
                                <a href="/plans">プランを見る</a>
                            @endcan
                            @can('shopExist')
                                <a href="/plans">プランを変える</a>
                            @endcan
                        @endcan
                        @can('isSubscribed')
                            <a href="/createshop">ショップ作成</a>
                            <a href="/plans">プランを変える</a>
                        @endcan
                    </div>
                </div>
                <div class="navbar-button m-b-header"><a href="#"><i><img src="{{ asset('../images/icon/1828940.png') }}"></i><i>ヘルプ</i></a></div>
                <div class="navbar-button"><a href="#"><i><img src="{{ asset('../images/icon/2571195.png') }}"></i><i>コンタクト</i></a></div>
                <div class="navbar-button">
                    @auth
                    <a href="#">
                        <div class="user">
                            <div class="user-image">
                                <div class="online-stat"></div>
                                <img src="{{ asset(Auth::user()->logo_type) }}" />
                            </div>
                            <div class="user-name">{{Auth::user()->firstname}} {{ Auth::user()->lastname }}</div>
                        </div>
                    </a>
                    @else
                    <a href="#"><i><img src="{{ asset('../images/icon/1946429.png') }}"></i><i>マイアカウント</i></a>
                    @endauth
                    <div class="dropdown">
                        @auth
                            <a href="/sellarticle">セールする</a>
                            <a href="/stock">ダッシュボード</a>
                            <a href="/u/logout">ログアウト</a>
                        @endauth
                        @guest
                            <a href="/register">会員登録</a>
                            <a href="/login">ログイン</a>
                        @endguest
                    </div>
                </div>
                <div class="navbar-button">
                    <div id="in-cart">
                    @if (Cart::Count()>0)
                        <div class="cart-number"><span>{{Cart::count()}}</span></div>
                    @endif
                    </div>
                    <a href="/shoppingcart"><i><img src="{{ asset('../images/icon/833314.png') }}"></i><i>カート</i></a>
                    @if (Cart::Count()>0)
                    <div class="dropdown">
                        <a href="/delete_all_cart">カートを空にする</a>
                    </div>
                    @endif
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
