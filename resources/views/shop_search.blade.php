<?php
	use Illuminate\Support\Str;
	$user = Auth::user();
?>
@include('templates/shop_header')
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
                            <img id="image_prev" class="resize-image">
                        </div>
                        <div class="cart-description_modal">
                            <form class="cm-form" method="post" action="{{ route('add_cover') }}" enctype="multipart/form-data">@csrf
                                <input class="inputfile" type="file" name="cover_pict" id="imagetemp"/>
                                <input id="cm_title" type="text" name="title" placeholder="タイトル"/>
                                <input class="add" type="submit" class="cm_upload" value="カバー写真を変える">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /modal -->
            <div class="cover">
                @if ($cover == 'not defined')
                <img class="cover-image" src="{{ asset('/images/default-cover.png') }}">
                @else
                <img class="cover-image" src="{{ asset($cover->big_image) }}">
                @endif
                @can('isAdmin')
                    <div class="shop-image">
                        <div class="modif-cover">
                            <button id="add_cm" class="add img-button-color"><i class="fas fa-image"></i> <i class='add-txt'>カバーを変える</i></button>
                        </div>
                    </div>
                @endcan
            </div>
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