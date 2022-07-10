@include('templates/shop_header')
        <div class="shop_products">
            <div class="article_upload">
            <h2>商品を加える</h2>
                <div class="form-group-article">
                    <h4>情報</h4>
                    <p>古い商品と壊れた商品などを売るのは禁止です。</p>
                    <div class="article_image"><img id="article_img" src="{{ asset('../images/default-image.jpg') }}"></div><br/>
                    @if(session('error_log'))
                    <div class="danger">
                    {{session('error_log')}} <a href="#">Click here</a>
                    </div><br/>
                    @endif
                    @if(session('success'))
                    <div class="success"><br>
                    {{session('success')}} <a href="#">Click here</a>
                    </div><br/>
                    @endif
                    <form method="post" onsubmit="addbreak()" action="/{{$shop->shop_name}}/upload_article" enctype="multipart/form-data">@csrf
                        <div class="img-button"><input class="inputfile" type="file" name="image" id="imagetemp_art"/></div><br/>
                        <div style="color:red"><span>@error('image'){{$message}}@enderror</span></div>
                        <div class="input-price">
                            <input type="text" name="name" placeholder="商品名"><br/>
                            <div style="color:red"><span>@error('name'){{$message}}@enderror</span></div>
                        </div>
                        <div class="input-price">
                            <span>¥</span>
                            <input type="numeric" id="art_price" name="price" placeholder="価格"><br/>
                            <div style="color:red"><span>@error('price'){{$message}}@enderror</span></div>
                        </div>
                        <div class="categorie">
                            <select name="categorie">
                                <option>カテゴリー</option>
                                @foreach ($categories_articles as $cat)
                                <option value="{{$cat->id_categorie}}">{{$cat->categorie}}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="text" name="size" placeholder="サイズ"><br/>
                        <div style="color:red"><span>@error('size'){{$message}}@enderror</span></div>
                        <div class="color_checkbox"><input id="wcolor" type="checkbox" onclick="drop()"><label>カラーを選択する</label></div>
                        <div id="check_color">
                            <select name="color">
                                <option>ありません</option>
                                <option>black</option>
                                <option>Red</option>
                            </select><br/>
                            <input type="text" name="input_color" placeholder="他のカラー">
                        </div>
                        <input type="number" name="quantity" id="quantity" placeholder="量"><br/>
                        <div style="color:red"><span>@error('quantity'){{$message}}@enderror</span></div>
                        <textarea id="desc" name="description"></textarea><br/>
                        <textarea placeholder="商品に関して" id="description"></textarea><br/>
                        <div class="counter_sell"><i id="number">1000</i></div><br/>
                        <input type="submit" name="Submit" class="articlesubmit" value="商品を売る">
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>


