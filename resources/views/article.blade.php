@include('templates/header')
        <div class="contain">
            <div class="content">
                    <div class="split">
                    <img id="img_article" src="{{ asset($article->large_images) }}"><br/><hr/>
                    <table class="article-table">
                        <tr>
                            <th>商品名:</th>
                            <td>{{ $article->article_name }}</td>
                        </tr>
                        <tr>
                            <th>価格:</th>
                            <td>{{ number_format($article->price) }}<span>¥</span></td>
                        </tr>
                        <tr>
                            <th>カテゴリー:</th>
                            <td>{{ $article->categorie }}</td>
                        </tr>
                        <tr>
                            <th>サイズ:</th>
                            <td>{{ $article->sizes }}</td>
                        </tr>
                        <tr>
                            <th>カラー:</th>
                            <td>{{ $article->color }}</td>
                        </tr>
                        <tr>
                            <th>量:</th>
                            <td>{{ $article->quantity }}</td>
                        </tr>
                    </table>
                    @if ($article->descriptions!=null)
                    <div class="article-description">
                        <h4>商品に関して:</h4>
                        <p>{!! $article->descriptions !!}</p>
                    </div>
                    @endif
                    @if(Auth::check() && $article->id == Auth::id())
                    @else
                    <form class="form-desc" method="post" action="/add_cart">@csrf
                        <input type="hidden" name="article" value="{{ $article->id_article }}">
                        <input type="number" name="quantity" placeholder="Quantity"><br/>
                        <span id="error" style="color:red">@error('quantity'){{$message}}@enderror</span><br/>
                        @if(session('success'))<span id="error" style="color:red">{{session('success')}}</span><br/>@endif
                        <button type="submit" class="articlesubmit"><i class="fas fa-shopping-cart" aria-hidden="true"></i> <span>Add to cart</span></button>
                    </form>
                    @endif
                </div>
                <div class="pub-box">
                    <div class="pub"></div>
                    <div class="pub"></div>
                </div>
            </div>
        </div>
@include('templates/footer')