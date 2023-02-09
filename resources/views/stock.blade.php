@include('templates/header')
        <div class="stock-contain">
            <div class="stock-content">
                <div class="side">
                    <div class="side-nav">
                        <ul>
                            <li>
                                <div class="dashboard-surf active-surf">
                                    <div class="dashboard-surf-icon">
                                        <span><i class="fas fa-box surf-icon v-btn" aria-hidden="true"></i></span>
                                    </div>
                                    <div class="dashboard-surf-txt v-btn"><span>Stock</span></div>
                                <div>
                            </li>
                            <li>
                                <a href="/solded">
                                    <div class="dashboard-surf">
                                        <div class="dashboard-surf-icon">
                                            <span><i class="fas fa-box-open surf-icon v-btn" aria-hidden="true"></i></span>
                                        </div>
                                    <div class="dashboard-surf-txt v-btn"><span>Vendu</span></div>
                                </a>
                            </li>
                            <li>
                                <a href="/purchased">
                                    <div class="dashboard-surf">
                                        <div class="dashboard-surf-icon">
                                            <span><i class="fas fa-inbox surf-icon v-btn" aria-hidden="true"></i></span>
                                        </div>
                                    <div class="dashboard-surf-txt v-btn"><span>Acheté</span></div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="stock">
                    <h2>Stock</h2>
                    <div class="loader" role="status" area-hidden="true" style="display: none;">
                        <img src="{{ asset('/images/load/ico-loading.gif') }}">
                        <span class="sr-only">loading...</span>
                    </div>
                    <div style="color:red"><span id="error"></span></div>
                    <div id="st_table">
                    @if (count($articles)>0)
                        <table class="tab">
                            <tr>
                                <th>Image</th>
                                <th><a class="col-sort" id="sort-name" data-order="desc" href="{!! route('sort',['page'=>$current_page]) !!}">Article</a></th>
                                <th><a class="col-sort" id="sort-size" data-order="desc" href="{!! route('sort',['page'=>$current_page]) !!}">Taille</a></th>
                                <th><a class="col-sort" id="sort-color" data-order="desc" href="{!! route('sort',['page'=>$current_page]) !!}">Couleur</a></th>
                                <th><a class="col-sort" id="sort-quantity" data-order="desc" href="{!! route('sort',['page'=>$current_page]) !!}">Quantité</a></th>
                                <th><a class="col-sort" id="sort-price" data-order="desc" href="{!! route('sort',['page'=>$current_page]) !!}">Prix (Dollar)</a></th>
                                <th>Modifier</th>
                                <th><a class="col-sort" id="sort-total" data-order="desc" href="{!! route('sort',['page'=>$current_page]) !!}">Total</a></th>
                            </tr>
                            <!-- tr -->
                            @foreach ($articles as $art)
                                <tr>
                                    <td><a href="up_article/{{ $art->id_article }}/{{ $art->article_name }}"><img class="img-cart" src="{{ asset($art->small_images) }}"></a></td>
                                    <td><a href="up_article/{{ $art->id_article }}/{{ $art->article_name }}">{{ $art->article_name }}</a></td>
                                    <td>{{ $art->sizes }}</td>
                                    <td>{{ $art->color }}</td>
                                    <td id="quantity">{{ $art->quantity }}</td>
                                    <td id="price">{{ number_format($art->price,2,'.',',') }} $</td>
                                    <td>
                                        <a class="minus" href="{!! route('update_article', ['action'=>'minus','article'=>$art->id_article]) !!}">
                                            <button><i class="fas fa-minus minus-btn"></i></button>
                                        </a>
                                        <input id="quantity" name="quantity" placeholder="1" type="number" style="max-width:50px">
                                        <a class="plus" href="{!! route('update_article', ['action'=>'plus','article'=>$art->id_article]) !!}">
                                            <button><i class="fas fa-plus plus-btn"></i></button>
                                        </a>
                                        <a class="del" href="{!! route('delete_article', ['article'=>$art->id_article,'name'=>$art->article_name]) !!}">
                                            <button class="tab-delete"><i class="fas fa-times"></i></button>
                                        </a>
                                    </td>
                                    <td id="total">{{ number_format($art->total_price,2,'.',',') }} $</td>
                                </tr>
                            @endforeach
                            <!-- /tr -->
                        </table><br/>
                        @elseif (count($articles)<=0)
                            <div class="lot">
                                <lottie-player src="{{asset('../lotties/108106-empty-cart.json')}}" background="transparent"  speed="0.6"  style="width: 30%;margin:auto" loop autoplay></lottie-player>
                                <span>Vous n'avez pas d'article dans votre stock</span>
                            </div>
                        @endif
                        <div class="page-list">
                            @if ($total_page>1)
                                @if ($total_page < 6)
                                    @if ($prev_page > 0)
                                        <div class="page-surf"><a href="/stock/{{$prev_page}}/{{$order}}/{{$col_name}}"><button><< </button></a></div>
                                    @endif
                                    @for ($page = 1;$page<=$total_page;$page++)
                                        @if ($current_page == $page)
                                        <div class="page-white">{{$page}}</div>
                                        @else
                                        <div class="page"><a href="/stock/{{$page}}/{{$order}}/{{$col_name}}"><button>{{$page}}</button></a></div>
                                        @endif
                                    @endfor
                                    @if ($next_page<=$total_page)
                                        <div class="page-surf"><a href="/stock/{{$next_page}}/{{$order}}/{{$col_name}}"><button>>> </button></a></div>
                                    @endif
                                @else  
                                    @if ($current_page < 4)
                                        @if ($prev_page > 0)
                                            <div class="page-surf"><a href="/stock/{{$prev_page}}/{{$order}}/{{$col_name}}"><button><< </button></a></div>
                                        @endif
                                        @for ($page = 1;$page<= 4;$page++)
                                            @if ($current_page == $page)
                                            <div class="page-white">{{$page}}</div>
                                            @else
                                            <div class="page"><a href="/stock/{{$page}}/{{$order}}/{{$col_name}}"><button>{{$page}}</button></a></div>
                                            @endif
                                        @endfor
                                        <span> ...</span>
                                        <div class="page"><a href="/stock/{{$total_page-1}}/{{$order}}/{{$col_name}}"><button>{{$total_page-1}}</button></a></div>
                                        <div class="page"><a href="/stock/{{$total_page}}/{{$order}}/{{$col_name}}"><button>{{$total_page}}</button></a></div>
                                        <div class="page-surf"><a href="/stock/{{$next_page}}/{{$order}}/{{$col_name}}"><button>>> </button></a></div>
                                    @else
                                        <div class="page-surf"><a href="/stock/{{$prev_page}}/{{$order}}/{{$col_name}}"><button><< </button></a></div>
                                        <div class="page"><a href="/stock/1/{{$order}}/{{$col_name}}"><button>1</button></a></div>
                                        <span> ...</span>
                                        <div class="page"><a href="/stock/{{$current_page-1}}/{{$order}}/{{$col_name}}"><button>{{$current_page-1}}</button></a></div>
                                        <div class="page-white">{{$current_page}}</div>
                                        @if($current_page+1<$total_page)
                                            <div class="page"><a href="/stock/{{$current_page+1}}/{{$order}}/{{$col_name}}"><button>{{$current_page+1}}</button></a></div>
                                        @endif
                                        @if($current_page<$total_page-2)
                                        <span> ...</span>
                                        @endif
                                        @if($current_page<$total_page)
                                            <div class="page"><a href="/stock/{{$total_page}}/{{$order}}/{{$col_name}}"><button>{{$total_page}}</button></a></div>
                                        @endif
                                        @if($current_page<$total_page)
                                            <div class="page-surf"><a href="/stock/{{$next_page}}/{{$order}}/{{$col_name}}"><button>>> </button></a></div>
                                        @endif
                                    @endif
                                @endif
                            @endif
                        </div>
                    </div>    
                </div>
            </div>
        </div>
@include('templates/footer')