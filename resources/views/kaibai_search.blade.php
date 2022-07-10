@include('templates/header')
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
                            <div class="u-container-style u-image u-layout-cell u-left-cell u-size-20 u-image-1" data-image-width="1000" data-image-height="1500">
                            <div class="u-container-layout u-container-layout-1"></div>
                            </div>
                            <div class="u-container-style u-layout-cell u-right-cell u-size-40 u-layout-cell-2">
                            <div class="u-container-layout u-valign-middle u-container-layout-2">
                                <h2 class="u-text u-text-body-alt-color u-text-1">Client Reviews</h2>
                                <p class="u-text u-text-body-alt-color u-text-default u-text-2">" I have known and worked closely with Rick and Liza Looser, and the fine folks at The Agency, for almost two decades and feel fully confident in expressing my endorsement. There is not a finer, more dedicated group of people offering marketing communications and public affairs capabilities and services. I first met Rick and Liza when I managed earned and paid media relations for Litton Industries, now Northrop Grumman Corporation."</p>
                                <h5 class="u-text u-text-body-alt-color u-text-3">Net Reynolds</h5>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
                <a class="u-absolute-vcenter u-carousel-control u-carousel-control-prev u-text-body-alt-color u-block-0754-3" href="#carousel_0b63" role="button" data-u-slide="prev">
                <span aria-hidden="true">
                    <svg viewBox="0 0 256 256"><g><polygon points="207.093,30.187 176.907,0 48.907,128 176.907,256 207.093,225.813 109.28,128"></polygon>
                    </g></svg>
                </span>
                <span class="sr-only">
                    <svg viewBox="0 0 256 256"><g><polygon points="207.093,30.187 176.907,0 48.907,128 176.907,256 207.093,225.813 109.28,128"></polygon>
                    </g></svg>
                </span>
                </a>
                <a class="u-absolute-vcenter u-carousel-control u-carousel-control-next u-text-body-alt-color u-block-0754-4" href="#carousel_0b63" role="button" data-u-slide="next">
                <span aria-hidden="true">
                    <svg viewBox="0 0 306 306"><g id="chevron-right"><polygon points="94.35,0 58.65,35.7 175.95,153 58.65,270.3 94.35,306 247.35,153"></polygon>
                    </g></svg>
                </span>
                <span class="sr-only">
                    <svg viewBox="0 0 306 306"><g id="chevron-right"><polygon points="94.35,0 58.65,35.7 175.95,153 58.65,270.3 94.35,306 247.35,153"></polygon>
                    </g></svg>
                </span>
                </a>
        </section>
        <div class="content">
            <div class="loader" role="status" area-hidden="true" style="display: none">
                    <!-- <img src="assets/images/load/ico-loading.gif"> -->
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
                                    <img src="{{ asset($art->small_images) }}" alt="" />
                                    <h2 class="price">{{ number_format($art->price) }}<span>¥</span></h2>
                                    <a href="/article/{{$art->id_article}}/{{$art->article_name}}"><p class="title">{{$art->article_name}}</p></a>
                                    @if(Auth::check() && $art->id == Auth::id())
                                    @else
                                    <button class="cart_button" href="{!! route('add_to_cart', ['article'=>$art->id_article]) !!}" id="add" ><i class="fas fa-shopping-cart" aria-hidden="true"></i> <span class="v-btn">Add to cart</span></button>
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
                @if ($prev_page > 0)
                    <div class="page-surf"><a href="/articles/{{$search}}/page/{{$prev_page}}"><button><< </button></a></div>
                @endif
                @for ($page = 1;$page<=$total_page;$page++)
                    @if ($current_page == $page)
                    <div class="page-white">{{$page}}</div>
                    @else
                    <div class="page"><a href="/articles/{{$search}}/page/{{$page}}"><button>{{$page}}</button></a></div>
                    @endif
                @endfor
                @if ($next_page<=$total_page)
                    <div class="page-surf"><a href="/articles/{{$search}}/page/{{$next_page}}"><button>>> </button></a></div>
                @endif
            @endif
        </div>
</div>
@include('templates/footer')