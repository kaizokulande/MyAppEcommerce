@inject('cart', 'Gloudemans\Shoppingcart\Facades\Cart')
@inject('bs_cont', 'App\Http\Controllers\Base_controller')
@php
    $categories = $bs_cont::getCategories();
    $user = Auth::user();
    $cart_quantity = $bs_cont::getCartarticlequantity();
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
<title>MyApp</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('../css/style.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('../css/media.css') }}" rel="stylesheet" type="text/css" media="screen" />
    <link href="{{ asset('../css/page.css') }}" rel="stylesheet" type="text/css" media="screen" />
    <link rel="stylesheet" type="text/css"  href="{{ asset('../font/css/fontawesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('../font/css/solid.css') }}">
    <link rel="stylesheet" type="text/css"  href="{{ asset('../font/css/all.min.css') }}">
    <script type="text/javascript" src="{{ asset('../js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('../js/script.js') }}"></script>
    <script type="text/javascript" src="{{ asset('../js/page.js') }}"></script>
    <link rel = "icon" href = "{{ asset('../images/logo_header.png') }}"  type = "image/x-icon">
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
</head>
<body>
    <header>
        <div class="nav">
            <a class="logo" href="/"><img class="logo-image" src="{{ asset('../images/logo_header.png') }}"/></a></span>
            <form class="search" action="/articles" method="get">
                <input name="search" placeholder="Recherche" type="text">
                <button type="submit" class="submitbtn"><i class="fas fa-search" aria-hidden="true"></i></button>
            </form>

        </div><!-- end header -->
        <div class="navbar">
            <div class="wrap-buttons">
                <div class="navbar-button m-b-header"><a href="/"><i><img src="{{ asset('../images/icon/2631151.png') }}"></i><i>Acceuil</i></a></div>
                <div class="navbar-button">
                    <a href="#"><i><img src="{{ asset('../images/icon/1141964.png') }}"></i><i>Categories</i></a>
                    <div class="dropdown cat-drop">
                        @foreach ($categories as $cat)
                            <a href="/cat/{{$cat->categorie}}">{{$cat->categorie}}</a>
                        @endforeach
                    </div>
                </div>
                <div class="navbar-button m-b-header"><a href="#"><i><img src="{{ asset('../images/icon/1828940.png') }}"></i><i>Aide</i></a></div>
                <div class="navbar-button"><a href="#"><i><img src="{{ asset('../images/icon/2571195.png') }}"></i><i>Contact</i></a></div>
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
                    <a href="#"><i><img src="{{ asset('../images/icon/1946429.png') }}"></i><i>Mon compte</i></a>
                    @endauth
                    <div class="dropdown">
                        @auth
                            <a href="/sellarticle">Vendre</a>
                            <a href="/stock">Dashboard</a>
                            <a href="/u/logout">Logout</a>
                        @endauth
                        @guest
                            <a href="/register">Inscription</a>
                            <a href="/login">Login</a>
                        @endguest
                    </div>
                </div>
                <div class="navbar-button">
                    <div id="in-cart">
                    @if (Auth::check())
                        @if ($cart_quantity>0)
                        <div class="cart-number"><span>{{$cart_quantity}}</span></div>
                        @endif
                    @else
                        @if (Cart::Count()>0)
                            <div class="cart-number"><span>{{Cart::count()}}</span></div>
                        @endif
                    @endif
                    </div>
                    <a href="#"><i><img src="{{ asset('../images/icon/833314.png') }}"></i><i>Panier</i></a>
                    <div class="dropdown cart-drop">
                        <a href="/shoppingcart">Voir le panier</a>
                        @if (Auth::check())
                            @if ($cart_quantity>0)
                            <a href="/delete_all_cart">Vider le panier</a>
                            @endif
                        @else
                            @if (Cart::Count()>0)
                            <a href="/delete_all_cart">Vider le panier</a>
                            @endif
                        @endif
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

