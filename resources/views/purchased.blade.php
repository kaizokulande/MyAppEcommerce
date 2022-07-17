@include('templates/header')
        <div class="stock-contain">
            <div class="stock-content">
                <div class="side">
                    <div class="side-nav">
                        <ul>
                            <li>
                                <a href="/stock">
                                    <div class="dashboard-surf">
                                        <div class="dashboard-surf-icon">
                                            <span><i class="fas fa-box surf-icon v-btn" aria-hidden="true"></i></span>
                                        </div>
                                        <div class="dashboard-surf-txt v-btn"><span>ストック</span></div>
                                    <div>
                                </a>
                            </li>
                            <li>
                                <a href="/solded">
                                    <div class="dashboard-surf">
                                        <div class="dashboard-surf-icon">
                                            <span><i class="fas fa-box-open surf-icon v-btn" aria-hidden="true"></i></span>
                                        </div>
                                    <div class="dashboard-surf-txt v-btn"><span>売られた商品</span></div>
                                </a>
                            </li>
                            <li>
                                <div class="dashboard-surf active-surf">
                                    <div class="dashboard-surf-icon">
                                        <span><i class="fas fa-inbox surf-icon v-btn" aria-hidden="true"></i></span>
                                    </div>
                                <div class="dashboard-surf-txt v-btn"><span>買った商品</span></div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="stock">
                    <h2>買った商品</h2>
                    <div class="loader" role="status" area-hidden="true" style="display: none;">
                        <img src="{{ asset('/images/load/ico-loading.gif') }}">
                        <span class="sr-only">loading...</span>
                    </div>
                    <div style="color:red"><span id="error"></span></div>
                    <div id="st_table">
                        <table class="tab">
                            <tr>
                                <th><a class="col-sort" id="sort-pdate" data-order="desc" href="{!! route('sort_purchased',['page'=>$current_page]) !!}">Dates</a></th>
                                <th>Picture</th>
                                <th><a class="col-sort" id="sort-name" data-order="desc" href="{!! route('sort_purchased',['page'=>$current_page]) !!}">Articles</a></th>
                                <th><a class="col-sort" id="sort-size" data-order="desc" href="{!! route('sort_purchased',['page'=>$current_page]) !!}">sizes</a></th>
                                <th><a class="col-sort" id="sort-color" data-order="desc" href="{!! route('sort_purchased',['page'=>$current_page]) !!}">Color</a></th>
                                <th><a class="col-sort" id="sort-quantity" data-order="desc" href="{!! route('sort_purchased',['page'=>$current_page]) !!}">Quantity</a></th>
                                <th><a class="col-sort" id="sort-price" data-order="desc" href="{!! route('sort_purchased',['page'=>$current_page]) !!}">Prix yen</a></th>
                                <th>Delete</th>
                                <th><a class="col-sort" id="sort-total" data-order="desc" href="{!! route('sort_purchased',['page'=>$current_page]) !!}">Total</a></th>
                            </tr>
                            <!-- tr -->
                            @foreach ($articles as $art)
                                <tr>
                                    <td>@php echo strftime('%Y %B %d %A', strtotime($art->purchase_dates)) @endphp</td>
                                    <td><img class="img-cart" src="{{ asset($art->small_images) }}"></td>
                                    <td>{{ $art->article_name }}</td>
                                    <td>{{ $art->sizes }}</td>
                                    <td>{{ $art->color }}</td>
                                    <td id="quantity">{{ $art->quantity }}</td>
                                    <td id="price">{{ number_format($art->price) }}¥</td>
                                    <td>
                                        <a class="del" href="{!! route('delete_p_article', ['article'=>$art->id_article]) !!}">
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
                                        <div class="page-surf"><a href="/purchased/{{$prev_page}}/{{$order}}/{{$col_name}}"><button><< </button></a></div>
                                    @endif
                                    @for ($page = 1;$page<=$total_page;$page++)
                                        @if ($current_page == $page)
                                        <div class="page-white">{{$page}}</div>
                                        @else
                                        <div class="page"><a href="/purchased/{{$page}}/{{$order}}/{{$col_name}}"><button>{{$page}}</button></a></div>
                                        @endif
                                    @endfor
                                    @if ($next_page<=$total_page)
                                        <div class="page-surf"><a href="/purchased/{{$next_page}}/{{$order}}/{{$col_name}}"><button>>> </button></a></div>
                                    @endif
                                @else  
                                    @if ($current_page < 4)
                                        @if ($prev_page > 0)
                                            <div class="page-surf"><a href="/purchased/{{$prev_page}}/{{$order}}/{{$col_name}}"><button><< </button></a></div>
                                        @endif
                                        @for ($page = 1;$page<= 4;$page++)
                                            @if ($current_page == $page)
                                            <div class="page-white">{{$page}}</div>
                                            @else
                                            <div class="page"><a href="/purchased/{{$page}}/{{$order}}/{{$col_name}}"><button>{{$page}}</button></a></div>
                                            @endif
                                        @endfor
                                        <span> ...</span>
                                        <div class="page"><a href="/purchased/{{$total_page-1}}/{{$order}}/{{$col_name}}"><button>{{$total_page-1}}</button></a></div>
                                        <div class="page"><a href="/purchased/{{$total_page}}/{{$order}}/{{$col_name}}"><button>{{$total_page}}</button></a></div>
                                        <div class="page-surf"><a href="/purchased/{{$next_page}}/{{$order}}/{{$col_name}}"><button>>> </button></a></div>
                                    @else
                                        <div class="page-surf"><a href="/purchased/{{$prev_page}}/{{$order}}/{{$col_name}}"><button><< </button></a></div>
                                        <div class="page"><a href="/purchased/1/{{$order}}/{{$col_name}}"><button>1</button></a></div>
                                        <span> ...</span>
                                        <div class="page"><a href="/purchased/{{$current_page-1}}/{{$order}}/{{$col_name}}"><button>{{$current_page-1}}</button></a></div>
                                        <div class="page-white">{{$current_page}}</div>
                                        @if($current_page+1<$total_page)
                                            <div class="page"><a href="/purchased/{{$current_page+1}}/{{$order}}/{{$col_name}}"><button>{{$current_page+1}}</button></a></div>
                                        @endif
                                        @if($current_page<$total_page-2)
                                        <span> ...</span>
                                        @endif
                                        @if($current_page<$total_page)
                                            <div class="page"><a href="/purchased/{{$total_page}}/{{$order}}/{{$col_name}}"><button>{{$total_page}}</button></a></div>
                                        @endif
                                        @if($current_page<$total_page)
                                            <div class="page-surf"><a href="/purchased/{{$next_page}}/{{$order}}/{{$col_name}}"><button>>> </button></a></div>
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