@include('templates/header')
@inject('bs_cont', 'App\Http\Controllers\Base_controller')
@php
    $cart_quantity = $bs_cont::getCartarticlequantity();
@endphp
    </header>
        <div class="contain">
            <div class="content">
                <div class="buy">
                    <h4>Panier</h4>
                    @if (session('message'))
                        <div class="success_message"><br/>{{ session('message') }}</div><br/>
                    @endif
                    <!-- card -->
                    @if (Auth::check())
                    @if ($cart_quantity == 0 || $cart_quantity==null)
                        <div class="lot">
                            <lottie-player src="{{asset('../lotties/108106-empty-cart.json')}}" background="transparent"  speed="0.6"  style="width: 20%;margin:auto" loop autoplay></lottie-player>
                            <span>Votre panier est vide</span>
                        </div>
                    @endif
                    @foreach ($articles as $art)
                    <div class="cart-card">
                        <div class="cart-inline">
                            <div class="cart-image">
                                <img alt="" class="u-expanded-width u-image u-image-default u-product-control u-image-1" src="{{ $art->small_images }}?>">
                            </div>
                            <div class="cart-art-desc">
                                <h5><strong>{{ $art->article_name }}</strong></h5>
                                <div><span class="color">Prix total: </span><span>{{ number_format($art->price * $art->qty,2,'.',',') }} $</span></div>
                                <div><span class="color">Quantité: </span><span><?php echo $art->qty ?></span></div>
                                <div><form class="cart-form" method="post" action="/qty_up">@csrf
                                    <input type="number" id="qtt" name="new_quantity">
                                    <input id="article" type="hidden" name="article" value="{{ $art->id_article }}">
                                    <input class="add" type="submit" value="Modifier" class="update-btn" name="action">
                                </form></div>
                                <a href="/buy?article={{ $art->id_article }}"><button class="cart_button"><i class="fas fa-shopping-cart"></i> <span class="v-btn">Acheter</span></button></a>
                                <a href="/delete_cart?article={{ $art->id_article }}"><button class="cart_delete"><i class="fas fa-times"></i> Supprimer</button></a>
                            </div>
                        </div>
                        <div class="cart-description">
                            <p>{!! $art->descriptions !!}</p>
                        </div>
                    </div>
                    @endforeach
                    @else
                    @if (Cart::count() == 0)
                        <div class="lot">
                            <lottie-player src="{{asset('../lotties/108106-empty-cart.json')}}" background="transparent"  speed="0.6"  style="width: 20%;margin:auto" loop autoplay></lottie-player>
                            <span>Votre panier est vide</span>
                        </div>
                    @endif
                    @foreach ($articles as $art)
                    <?php
                        $id = $art->id_article;
                        $cart_article = Cart::search(function ($cartItem, $rowId) use ($id) {
                            return $cartItem->id == $id;
                        });
                        $quantity = $cart_article->pluck('qty');
                        $total = $art->price * $quantity[0];
                    ?>
                    <div class="cart-card">
                        <div class="cart-inline">
                            <div class="cart-image">
                                <img alt="" class="u-expanded-width u-image u-image-default u-product-control u-image-1" src="{{ $art->small_images }}?>">
                            </div>
                            <div class="cart-art-desc">
                                <h5><strong>{{ $art->article_name }}</strong></h5>
                                <div><span class="color">Prix total: </span><span>{{ number_format($total,2,'.',',') }} $</span></div>
                                <div><span class="color">Quantité: </span><span><?php echo $quantity[0] ?></span></div>
                                <div><form class="cart-form" method="post" action="/qty_up">@csrf
                                    <input type="number" id="qtt" name="new_quantity">
                                    <input id="article" type="hidden" name="article" value="{{ $art->id_article }}">
                                    <input class="add" type="submit" value="Modifier" class="update-btn" name="action">
                                </form></div>
                                <a href="/buy?article={{ $art->id_article }}"><button class="cart_button"><i class="fas fa-shopping-cart"></i> <span class="v-btn">Acheter</span></button></a>
                                <a href="/delete_cart?article={{ $art->id_article }}"><button class="cart_delete"><i class="fas fa-times"></i> Supprimer</button></a>
                            </div>
                        </div>
                        <div class="cart-description">
                            <p>{!! $art->descriptions !!}</p>
                        </div>
                    </div>
                    @endforeach
                    @endif
                    <!-- /card -->
                </div>
            </div>
        </div>
@include('templates/footer')