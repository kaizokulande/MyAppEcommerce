@include('./templates/header')
<div class="contain">
        <!------------Carousel---------------->
        <section class="u-carousel u-slide u-block-0754-1" id="carousel_0b63" data-interval="5000" data-u-ride="carousel">
            <ol class="u-absolute-hcenter u-carousel-indicators u-block-0754-2">
                <li data-u-target="#carousel_0b63" class="u-active u-grey-30" data-u-slide-to="0"></li>
            </ol>
            <div class="u-carousel-inner" role="listbox">
                <div class="u-active u-align-left u-carousel-item u-clearfix u-palette-5-base u-section-1-1">
                    <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
                    <div class="u-clearfix u-expanded-width u-layout-wrap u-layout-wrap-1">
                        <div class="u-layout">
                        <div class="u-layout-row">
                            <div class="u-container-style u-image u-layout-cell u-left-cell u-size-20 u-image-1" style="background-image: url({{ asset('../images/header_img.png') }});" data-image-width="1000" data-image-height="1500">
                                <div class="u-container-layout u-container-layout-1"></div>
                                </div>
                                <div class="u-container-style u-layout-cell u-right-cell u-size-40 u-layout-cell-2">
                                <div class="u-container-layout u-valign-middle u-container-layout-2">
                                    <h2 class="u-text u-text-body-alt-color u-text-1">Information</h2>
                                    <p class="u-text u-text-body-alt-color u-text-default u-text-2">N'hésitez pas à tester ce site à votre guise. </p>
                                    <p class="u-text u-text-body-alt-color u-text-default u-text-2">Vous pouvez utiliser des faux comptes email (ex:test@test.com) étant donné que vous ne recevrez aucun email de confirmation au moment de l'inscription.</p>
                                    <p class="u-text u-text-body-alt-color u-text-default u-text-2">Les utilisateurs ne pourront pas acheter les articles qu'ils ont mis en vente.</p>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
        </section>
        <div class="content">
            <div class="loader" role="status" area-hidden="true" style="display: none;">
                <img src="{{ asset('/images/load/ico-loading.gif') }}">
                <span class="sr-only">loading...</span>
            </div>
            <!--/product_item-->
            <div class="products-wrap">
                <div class="products">
                    <h3>Articles</h3>
                    @foreach ($articles as $art)
                    <div class="product">
				        <div class="product-image-wrapper">
                            @if(Auth::check() && $art->id == Auth::id() )
                                <div class="self_article"></div>
                            @endif
                            <div class="single-products">
                                <div class="productinfo text-center">
                                    <img src="{{ asset($art->small_images) }}" alt=""/>
                                    <h2 class="price">{{ number_format($art->price,2,'.',',') }}<span>$</span></h2>
                                    <a href="/article/{{$art->id_article}}/{{$art->article_name}}"><p class="title">{{$art->article_name}}</p></a>
                                    @if(Auth::check() && $art->id == Auth::id())
                                    @else
                                    <button class="cart_button" href="{!! route('add_to_cart', ['article'=>$art->id_article]) !!}" id="add" ><i class="fas fa-shopping-cart v-btn" aria-hidden="true"></i> <span class="v-btn">Ajouter au panier</span></button>
                                    @endif
                                </div>
                            </div>
				        </div>
			        </div>
                    @endforeach
                <!-- products -->
                </div>
            </div>
        </div>
        <div class="page-list">
            @if ($total_page>1)
                @if ($total_page < 6)
                    @if ($prev_page > 0)
                        <div class="page-surf"><a href="/page/{{$prev_page}}"><button><< </button></a></div>
                    @endif
                    @for ($page = 1;$page<=$total_page;$page++)
                        @if ($current_page == $page)
                        <div class="page-white">{{$page}}</div>
                        @else
                        <div class="page"><a href="/page/{{$page}}"><button>{{$page}}</button></a></div>
                        @endif
                    @endfor
                    @if ($next_page<=$total_page)
                        <div class="page-surf"><a href="/page/{{$next_page}}"><button>>> </button></a></div>
                    @endif
                @else  
                    @if ($current_page < 4)
                        @if ($prev_page > 0)
                            <div class="page-surf"><a href="/page/{{$prev_page}}"><button><< </button></a></div>
                        @endif
                        @for ($page = 1;$page<= 4;$page++)
                            @if ($current_page == $page)
                            <div class="page-white">{{$page}}</div>
                            @else
                            <div class="page"><a href="/page/{{$page}}"><button>{{$page}}</button></a></div>
                            @endif
                        @endfor
                        <span> ...</span>
                        <div class="page"><a href="/page/{{$total_page-1}}"><button>{{$total_page-1}}</button></a></div>
                        <div class="page"><a href="/page/{{$total_page}}"><button>{{$total_page}}</button></a></div>
                        <div class="page-surf"><a href="/page/{{$next_page}}"><button>>> </button></a></div>
                    @else
                        <div class="page-surf"><a href="/page/{{$prev_page}}"><button><< </button></a></div>
                        <div class="page"><a href="/page/1"><button>1</button></a></div>
                        <span> ...</span>
                        <div class="page"><a href="/page/{{$current_page-1}}"><button>{{$current_page-1}}</button></a></div>
                        <div class="page-white">{{$current_page}}</div>
                        @if($current_page+1<$total_page)
                            <div class="page"><a href="/page/{{$current_page+1}}"><button>{{$current_page+1}}</button></a></div>
                        @endif
                        @if($current_page<$total_page-2)
                        <span> ...</span>
                        @endif
                        @if($current_page<$total_page)
                            <div class="page"><a href="/page/{{$total_page}}"><button>{{$total_page}}</button></a></div>
                        @endif
                        @if($current_page<$total_page)
                            <div class="page-surf"><a href="/page/{{$next_page}}"><button>>> </button></a></div>
                        @endif
                    @endif
                @endif
            @endif
        </div>
</div>
@include('templates/footer')