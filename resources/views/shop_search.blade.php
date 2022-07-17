@include('templates/shop_header')
    <div class="body-container">
        <div class="shop_products">
            <h2>商品</h2>
            <div class="loader" role="status" area-hidden="true" style="display: none;">
                <img src="{{ asset('/images/load/ico-loading.gif') }}">
                <span class="sr-only">loading...</span>
            </div>
			<!-- products -->
            <div class="products">
			@foreach ($articles as $art)
                <div class="product">
				    <div class="product-image-wrapper">
                        @if(Auth::check() && $shop->id==Auth::id())
                            <div class="self_article"></div>
                        @endif
                        <div class="single-products">
                            <div class="productinfo text-center">
                                <div class="product-img">
                                    <img src="{{ asset($art->small_images) }}" alt="" />
                                </div>
                                <h2 class="price">{{ number_format($art->price) }}<span>¥</span></h2>
                                <a href="/{{$shop->shop_name}}/article/{{$art->id_article}}/{{$art->article_name}}"><p class="title">{{$art->article_name}}</p></a>
                                @if(Auth::check() && $shop->id==Auth::id())
                                @else
                                <button class="cart_button" id="add-shop" href="{!! route('add_to_cart', ['article'=>$art->id_article]) !!}"><i class="fa fa-shopping-cart v-btn"></i> <span class="v-btn">Add to cart</span></button>
                                @endif
                            </div>
                        </div>
				    </div>
			    </div>
            @endforeach
            </div>
			<!-- /products -->
            <div class="page-list">
            @if ($total_page>1)
                @if ($total_page < 6)
                    @if ($prev_page > 0)
                        <div class="page-surf"><a href="/shop/{{$shop->shop_name}}/articles/{{$search}}/page/{{$prev_page}}"><button><< </button></a></div>
                    @endif
                    @for ($page = 1;$page<=$total_page;$page++)
                        @if ($current_page == $page)
                        <div class="page-white">{{$page}}</div>
                        @else
                        <div class="page"><a href="/shop/{{$shop->shop_name}}/articles/{{$search}}/page/{{$page}}"><button>{{$page}}</button></a></div>
                        @endif
                    @endfor
                    @if ($next_page<=$total_page)
                        <div class="page-surf"><a href="/shop/{{$shop->shop_name}}/articles/{{$search}}/page/{{$next_page}}"><button>>> </button></a></div>
                    @endif
                @else  
                    @if ($current_page < 4)
                        @if ($prev_page > 0)
                            <div class="page-surf"><a href="/shop/{{$shop->shop_name}}/articles/{{$search}}/page/{{$prev_page}}"><button><< </button></a></div>
                        @endif
                        @for ($page = 1;$page<= 4;$page++)
                            @if ($current_page == $page)
                            <div class="page-white">{{$page}}</div>
                            @else
                            <div class="page"><a href="/shop/{{$shop->shop_name}}/articles/{{$search}}/page/{{$page}}"><button>{{$page}}</button></a></div>
                            @endif
                        @endfor
                        <span> ...</span>
                        <div class="page"><a href="/shop/{{$shop->shop_name}}/articles/{{$search}}/page/{{$total_page-1}}"><button>{{$total_page-1}}</button></a></div>
                        <div class="page"><a href="/shop/{{$shop->shop_name}}/articles/{{$search}}/page/{{$total_page}}"><button>{{$total_page}}</button></a></div>
                        <div class="page-surf"><a href="/shop/{{$shop->shop_name}}/articles/{{$search}}/page/{{$next_page}}"><button>>> </button></a></div>
                    @else
                        <div class="page-surf"><a href="/shop/{{$shop->shop_name}}/articles/{{$search}}/page/{{$prev_page}}"><button><< </button></a></div>
                        <div class="page"><a href="/shop/{{$shop->shop_name}}/articles/{{$search}}/page/1"><button>1</button></a></div>
                        <span> ...</span>
                        <div class="page"><a href="/shop/{{$shop->shop_name}}/articles/{{$search}}/page/{{$current_page-1}}"><button>{{$current_page-1}}</button></a></div>
                        <div class="page-white">{{$current_page}}</div>
                        @if($current_page+1<$total_page)
                            <div class="page"><a href="/shop/{{$shop->shop_name}}/articles/{{$search}}/page/{{$current_page+1}}"><button>{{$current_page+1}}</button></a></div>
                        @endif
                        @if($current_page<$total_page-2)
                        <span> ...</span>
                        @endif
                        @if($current_page<$total_page)
                            <div class="page"><a href="/shop/{{$shop->shop_name}}/articles/{{$search}}/page/{{$total_page}}"><button>{{$total_page}}</button></a></div>
                        @endif
                        @if($current_page<$total_page)
                            <div class="page-surf"><a href="/shop/{{$shop->shop_name}}/articles/{{$search}}/page/{{$next_page}}"><button>>> </button></a></div>
                        @endif
                    @endif
                @endif
            @endif
            </div>
        </div>
    </div>
</body>
</html>