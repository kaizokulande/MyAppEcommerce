
@include('templates/shop_header')
        <div class="shop_products">
            <div class="article_upload">
            <h2>商品をアップデート</h2>
                <div class="form-group-article">
                    <h4>informations</h4>
                    <p>la vente d'article usee ou casse est strictement interdit</p>
                    <div class="article_image"><img id="article_img" src="{{ asset($article->small_images) }}"></div><br/>
                    @if(session('error_log'))
                    <div class="danger">
                    {{session('error_log')}} <a href="#">Click here</a>
                    </div><br/>
                    @endif
                    @if(session('success'))
                    <div class="success"><br>
                    {{session('success')}}
                    </div><br/>
                    @endif
                    <form method="post" onsubmit="addbreak()" action="/update_article/{{$article->id_article}}" enctype="multipart/form-data">@csrf
                        <div class="img-button"><input class="inputfile" type="file" name="image" id="imagetemp_art"/></div><br/>
                        <div style="color:red"><span>@error('image'){{$message}}@enderror</span></div>
                        <div class="input-price">
                            <input type="text" name="name" value="{{ $article->article_name }}" placeholder="Article Name"><br/>
                            <div style="color:red"><span>@error('name'){{$message}}@enderror</span></div>
                        </div>
                        <div class="input-price">
                            <span>¥</span>
                            <input type="numeric" id="art_price" name="price" value="{{ $article->price }}" placeholder="Article Price"><br/>
                            <div style="color:red"><span>@error('price'){{$message}}@enderror</span></div>
                        </div>
                        <div class="categorie">
                            <select name="categorie">
                                <option value="{{ $article->id_categorie }}">{{ $article->categorie }}</option>
                                @foreach ($categories_articles as $cat)
                                <option value="{{$cat->id_categorie}}">{{$cat->categorie}}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="text" name="size" value="{{ $article->sizes }}" placeholder="Article  Size"><br/>
                        <div style="color:red"><span>@error('size'){{$message}}@enderror</span></div>
                        <div class="color_checkbox"><input id="wcolor" type="checkbox" onclick="drop()"><label>With color</label></div>
                        <div id="check_color">
                            <select name="color">
                                <option value="{{ $article->color }}">{{ $article->color }}</option>
                                <option>black</option>
                                <option>Red</option>
                            </select><br/>
                        </div>
                        <input type="number" name="quantity" value="{{ $article->quantity }}" id="quantity" placeholder="quantity"><br/>
                        <div style="color:red"><span>@error('quantity'){{$message}}@enderror</span></div>
                        <textarea id="desc" name="description"></textarea><br/>
                        <textarea placeholder="article description" value="{{ $article->descriptions }}" id="description">{{ $article->descriptions }}</textarea><br/>
                        <div class="counter_sell"><i id="number">1000</i></div><br/>
                        <input type="submit" name="Submit" class="articlesubmit" value="ウェブサイト">
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>


