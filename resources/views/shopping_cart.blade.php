@include('templates/header')
    </header>
        <div class="contain">
            <div class="content">
                <div class="buy">
                    <h4>Your Cart</h4>
                    @if (session('message'))
                        <div class="success_message"><br/>{{ session('message') }}</div><br/>
                    @endif
                    @if (Cart::count() == 0)
                        <div class="cart_danger"><br/>Your cart is Empty</div><br/>
                    @endif
                    <!-- card -->
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
                                <div><span class="color">price: </span><span>{{ number_format($total) }}¥</span></div>
                                <div><span class="color">Quantity: </span><span><?php echo $quantity[0] ?></span></div>
                                <div><form class="cart-form" method="post" action="/qty_up">@csrf
                                    <input type="number" id="qtt" name="new_quantity">
                                    <input id="article" type="hidden" name="article" value="{{ $art->id_article }}">
                                    <input class="add" type="submit" value="Update" class="update-btn" name="action">
                                </form></div>
                                <a href="/buy?article={{ $art->id_article }}"><button class="cart_button"><i class="fas fa-shopping-cart"></i> <span class="v-btn">Buy</span></button></a>
                                <a href="/delete_cart?article={{ $art->id_article }}"><button class="cart_delete"><i class="fas fa-times"></i> Delete</button></a>
                            </div>
                        </div>
                        <div class="cart-description">
                            <p>{!! $art->descriptions !!} <br/> hahah</p>
                        </div>
                    </div>
                    @endforeach
                    <!-- /card -->
                </div>
            </div>
        </div>
@include('templates/footer')