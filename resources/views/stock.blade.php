@include('templates/header')
        <div class="stock-contain">
            <div class="stock-content">
                <div class="side">
                    <div class="side-nav">
                        <ul>
                            <li><a href="/solded">Solded articles</a></li>
                            <li><a href="/purchased">Purchased articles</a></li>
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
                        <table class="tab">
                            <tr>
                                <th>Picture</th>
                                <th><a class="col-sort" id="sort-name" data-order="desc" href="{!! route('sort',['page'=>$current_page]) !!}">Articles</a></th>
                                <th><a class="col-sort" id="sort-size" data-order="desc" href="{!! route('sort',['page'=>$current_page]) !!}">sizes</a></th>
                                <th><a class="col-sort" id="sort-color" data-order="desc" href="{!! route('sort',['page'=>$current_page]) !!}">Color</a></th>
                                <th><a class="col-sort" id="sort-quantity" data-order="desc" href="{!! route('sort',['page'=>$current_page]) !!}">Quantity</a></th>
                                <th><a class="col-sort" id="sort-price" data-order="desc" href="{!! route('sort',['page'=>$current_page]) !!}">Prix yen</a></th>
                                <th>Update</th>
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
                                    <td id="price">{{ number_format($art->price) }}¥</td>
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
                                    <td id="total">{{ number_format($art->total_price) }} ¥</td>
                                </tr>
                            @endforeach
                            <!-- /tr -->
                        </table><br/>
                        <div class="page-list">
                            @if ($total_page>1)
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
                            @endif
                        </div>
                    </div>    
                </div>
            </div>
        </div>
@include('templates/footer')