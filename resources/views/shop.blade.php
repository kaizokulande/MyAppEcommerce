@include('templates/shop_header')
    <div class="body-container">
        <div class="shop_products">
            <!-- modal -->
            <div id="cm_upload">
                <div class="cm-modal-contents">
                    <div class="cm-header-modal">
                        <span id="close">&times;</span>
                        <h5 class="title">カバーを変える</h5>
                    </div>
                    <div class="cm-body">
                        <div class="component">
                            <div class="overlay">
                                <div class="overlay-inner"></div>
                            </div>
                                <img id="image_preview" class="resize-image">
                        </div>
                        <div class="cart-description_modal">
                            <form class="cm-form">
                                <input type="file" class="inputfile" name="cover_pict" id="imagetemp"/>
                                <input id="cm_title" type="text" placeholder="タイトル"/>
                                <input class="add" id="js-crop" type="submit" class="cm_upload" value="カバー写真を変える">
                            </form>
                        </div>
                    </div>
                    <div class="m-footer">
                    </div>
                </div>
            </div>
            <!-- /modal -->
            <div class="cover">
                @can('isAdmin')
                    <div class="modif-cover"><button id="add_cm" class="add img-button-color"><i class="fas fa-image"></i> カバーを変える</button></div>
                @endcan
                @if ($cover == 'not defined')
                    <img class="cover-pict" src="{{ asset('/images/default-cover.png') }}">
                @else
                    <img class="cover-pict" src="{{ asset($cover->big_image) }}">
                @endif
            </div>
            <h2>商品</h2>
            <div class="loader-shop" role="status" area-hidden="true" style="display: none;">
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
                                <button class="cart_button" id="add-shop" href="{!! route('add_to_cart', ['article'=>$art->id_article]) !!}"><i class="fa fa-shopping-cart"></i><span class="v-btn">Add to cart</span></button>
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
                    @if ($prev_page > 0)
                        <div class="page-surf"><a href="/{{$shop->shop_name}}/page/{{$prev_page}}"><button><< </button></a></div>
                    @endif
                    @for ($page = 1;$page<=$total_page;$page++)
                        @if ($current_page == $page)
                        <div class="page-white">{{$page}}</div>
                        @else
                        <div class="page"><a href="/{{$shop->shop_name}}/page/{{$page}}"><button>{{$page}}</button></a></div>
                        @endif
                    @endfor
                    @if ($next_page<=$total_page)
                        <div class="page-surf"><a href="/{{$shop->shop_name}}/page/{{$next_page}}"><button>>> </button></a></div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</body>
</html>