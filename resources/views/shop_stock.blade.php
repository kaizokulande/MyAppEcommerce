
@include('templates/shop_header')
        <div class="shop_products">
            <div class="stock">
                <h2>ストック</h2>
                <div class="loader" role="status" area-hidden="true" style="display: none;">
                    <img src="{{ asset('/images/load/ico-loading.gif') }}">
                    <span class="sr-only">loading...</span>
                </div>
                <div style="color:red"><span id="error"></span></div>
                <div id="st_table">
                    <table class="tab">
                        <tr>
                            <th>Picture</th>
                            <th><a class="col-sort" id="sort-name" data-order="desc" href="{!! route('sort-shop',['page'=>$current_page]) !!}">Articles</a></th>
                            <th><a class="col-sort" id="sort-size" data-order="desc" href="{!! route('sort-shop',['page'=>$current_page]) !!}">sizes</a></th>
                            <th><a class="col-sort" id="sort-color" data-order="desc" href="{!! route('sort-shop',['page'=>$current_page]) !!}">Color</a></th>
                            <th><a class="col-sort" id="sort-quantity" data-order="desc" href="{!! route('sort-shop',['page'=>$current_page]) !!}">Quantity</a></th>
                            <th><a class="col-sort" id="sort-price" data-order="desc" href="{!! route('sort-shop',['page'=>$current_page]) !!}">Prix yen</a></th>
                            <th>Update</th>
                            <th><a class="col-sort" id="sort-total" data-order="desc" href="{!! route('sort-shop',['page'=>$current_page]) !!}">Total</a></th>
                        </tr>
                        <!-- tr -->
                        @foreach ($articles as $art)
                            <tr>
                                <td><a href="up_article/{{ $art->id_article }}/{{ $art->article_name }}"><img class="img-cart" src="{{ asset($art->small_images) }}"></a></td>
                                <td><a href="up_article/{{ $art->id_article }}/{{ $art->article_name }}">{{ $art->article_name }}</a></td>
                                <td>{{ $art->sizes }}</td>
                                <td>{{ $art->color }}</td>
                                <td id="quantity">{{ $art->quantity }}</td>
                                <td id="price">{{ number_format($art->price) }}¥</td>
                                <td>
                                    <a class="minus" href="{!! route('update_shop_article', ['action'=>'minus','article'=>$art->id_article]) !!}">
                                        <button><i class="fas fa-minus minus-btn"></i></button>
                                    </a>
                                    <input id="quantity" name="quantity" placeholder="1" type="number" style="max-width:50px">
                                    <a class="plus" href="{!! route('update_shop_article', ['action'=>'plus','article'=>$art->id_article]) !!}">
                                        <button><i class="fas fa-plus plus-btn"></i></button>
                                    </a>
                                    <a class="del" href="{!! route('delete_shop_article', ['article'=>$art->id_article,'name'=>$art->article_name]) !!}">
                                        <button class="tab-delete"><i class="fas fa-times"></i></button>
                                    </a>
                                </td>
                                <td id="total">{{ number_format($art->total_price) }} ¥</td>
                            </tr>
                        @endforeach
                        <!-- /tr -->
                    </table><br/>
                    <div class="page-list">
                        @if ($total_page>1)
                            @if ($total_page < 6)
                                @if ($prev_page > 0)
                                    <div class="page-surf"><a href="/{{$shop->shop_name}}/stock/{{$prev_page}}/{{$order}}/{{$col_name}}"><button><< </button></a></div>
                                @endif
                                @for ($page = 1;$page<=$total_page;$page++)
                                    @if ($current_page == $page)
                                    <div class="page-white">{{$page}}</div>
                                    @else
                                    <div class="page"><a href="/{{$shop->shop_name}}/stock/{{$page}}/{{$order}}/{{$col_name}}"><button>{{$page}}</button></a></div>
                                    @endif
                                @endfor
                                @if ($next_page<=$total_page)
                                    <div class="page-surf"><a href="/{{$shop->shop_name}}/stock/{{$next_page}}/{{$order}}/{{$col_name}}"><button>>> </button></a></div>
                                @endif
                            @else  
                                @if ($current_page < 4)
                                    @if ($prev_page > 0)
                                        <div class="page-surf"><a href="/{{$shop->shop_name}}/stock/{{$prev_page}}/{{$order}}/{{$col_name}}"><button><< </button></a></div>
                                    @endif
                                    @for ($page = 1;$page<= 4;$page++)
                                        @if ($current_page == $page)
                                        <div class="page-white">{{$page}}</div>
                                        @else
                                        <div class="page"><a href="/{{$shop->shop_name}}/stock/{{$page}}/{{$order}}/{{$col_name}}"><button>{{$page}}</button></a></div>
                                        @endif
                                    @endfor
                                    <span> ...</span>
                                    <div class="page"><a href="/{{$shop->shop_name}}/stock/{{$total_page-1}}/{{$order}}/{{$col_name}}"><button>{{$total_page-1}}</button></a></div>
                                    <div class="page"><a href="/{{$shop->shop_name}}/stock/{{$total_page}}/{{$order}}/{{$col_name}}"><button>{{$total_page}}</button></a></div>
                                    <div class="page-surf"><a href="/{{$shop->shop_name}}/stock/{{$next_page}}/{{$order}}/{{$col_name}}"><button>>> </button></a></div>
                                @else
                                    <div class="page-surf"><a href="/{{$shop->shop_name}}/stock/{{$prev_page}}/{{$order}}/{{$col_name}}"><button><< </button></a></div>
                                    <div class="page"><a href="/{{$shop->shop_name}}/stock/1/{{$order}}/{{$col_name}}"><button>1</button></a></div>
                                    <span> ...</span>
                                    <div class="page"><a href="/{{$shop->shop_name}}/stock/{{$current_page-1}}/{{$order}}/{{$col_name}}"><button>{{$current_page-1}}</button></a></div>
                                    <div class="page-white">{{$current_page}}</div>
                                    @if($current_page+1<$total_page)
                                        <div class="page"><a href="/{{$shop->shop_name}}/stock/{{$current_page+1}}/{{$order}}/{{$col_name}}"><button>{{$current_page+1}}</button></a></div>
                                    @endif
                                    @if($current_page<$total_page-2)
                                    <span> ...</span>
                                    @endif
                                    @if($current_page<$total_page)
                                        <div class="page"><a href="/{{$shop->shop_name}}/stock/{{$total_page}}/{{$order}}/{{$col_name}}"><button>{{$total_page}}</button></a></div>
                                    @endif
                                    @if($current_page<$total_page)
                                        <div class="page-surf"><a href="/{{$shop->shop_name}}/stock/{{$next_page}}/{{$order}}/{{$col_name}}"><button>>> </button></a></div>
                                    @endif
                                @endif
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>




