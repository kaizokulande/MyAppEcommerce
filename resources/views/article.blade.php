@include('templates/header')
        <div class="contain">
            <div class="content">
                    <div class="split">
                    <h2>{{ $article->article_name }}</h2>
                    <img id="img_article" src="{{ asset($article->large_images) }}"><br/><hr/>
                    <table class="article-table">
                        <tr>
                            <th>Nom:</th>
                            <td>{{ $article->article_name }}</td>
                        </tr>
                        <tr>
                            <th>Prix:</th>
                            <td>{{ number_format($article->price,2,'.',',') }}<span>$</span></td>
                        </tr>
                        <tr>
                            <th>Categorie:</th>
                            <td>{{ $article->categorie }}</td>
                        </tr>
                        <tr>
                            <th>Taille:</th>
                            <td>{{ $article->sizes }}</td>
                        </tr>
                        <tr>
                            <th>Couleur:</th>
                            <td>{{ $article->color }}</td>
                        </tr>
                        <tr>
                            <th>quantité:</th>
                            <td>{{ $article->quantity }}</td>
                        </tr>
                    </table>
                    @if ($article->descriptions!=null)
                    <div class="article-description">
                        <h4>Description:</h4>
                        <p>{!! $article->descriptions !!}</p>
                    </div>
                    @endif
                    @if(Auth::check() && $article->id == Auth::id())
                    @else
                    <form class="form-desc" method="post" action="/add_cart">@csrf
                        <input type="hidden" name="article" value="{{ $article->id_article }}">
                        <input type="number" name="quantity" placeholder="Quantité"><br/>
                        <span id="error" style="color:red">@error('quantity'){{$message}}@enderror</span><br/>
                        @if(session('success'))<span id="error" style="color:red">{{session('success')}}</span><br/>@endif
                        <button type="submit" class="articlesubmit"><i class="fas fa-shopping-cart" aria-hidden="true"></i> <span>Ajouter au panier</span></button>
                    </form>
                    @endif
                </div>
                <div class="pub-box">
                    <div class="pub"><img style="width:400px ;height:300px;" src="{{ asset('../images/pub.png') }}"/></div>
                    <div class="pub"><img style="width:400px ;height:300px;" src="{{ asset('../images/pub.png') }}"/></div>
                </div>
            </div>
        </div>
@include('templates/footer')