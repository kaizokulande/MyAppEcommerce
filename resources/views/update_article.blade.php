
@include('templates/header')
@inject('bs_cont', 'App\Http\Controllers\Base_controller')
@php
    $colors = $bs_cont::get_colors();
    $categories = $bs_cont::getCategories();
@endphp
<div class="contain">
    <div class="content">
    <div class="article_upload">
        <h4>Modifier l'article</h4>
        <div class="form-group-article">
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
            <form method="post" onsubmit="addbreak()" action="/article_update/{{ $article->id_article }}" enctype="multipart/form-data">@csrf
                <div class="img-button"><input class="inputfile" type="file" name="image" id="imagetemp_art"/></div><br/>
                <div style="color:red"><span>@error('image'){{$message}}@enderror</span></div>
                <div class="input-price">
                    <input type="text" name="name" value="{{ $article->article_name }}" placeholder="Nom"><br/>
                    <div style="color:red"><span>@error('name'){{$message}}@enderror</span></div>
                </div>
                <div class="input-price">
                    <span>$</span>
                    <input type="numeric" id="art_price" value="{{ $article->price }}" name="price" placeholder="Prix"><br/>
                    <div style="color:red"><span>@error('price'){{$message}}@enderror</span></div>
                </div>
                <div class="categorie">
                    <select name="categorie">
                        <option value="{{$article->id_categorie}}">{{ $article->categorie }}</option>
                        @foreach ($categories as $cat)
                        <option value="{{$cat->id_categorie}}">{{$cat->categorie}}</option>
                        @endforeach
                    </select>
                </div>
                <input type="text" name="size" value="{{ $article->sizes }}" placeholder="Taille"><br/>
                <div style="color:red"><span>@error('size'){{$message}}@enderror</span></div>
                <div class="color_checkbox"><input id="wcolor" type="checkbox" onclick="drop()"><label>Choisir une couleur</label></div>
                <div id="check_color">
                    <select name="color">
                        <option value="{{ $article->color }}">{{ $article->color }}</option>
                        @foreach ($colors as $c)
                            <option value="{{$c->color_jap}}">{{$c->color_fr}}</option>
                        @endforeach
                    </select><br/>
                </div>
                <input type="number" value="{{ $article->quantity }}"name="quantity" id="quantity" placeholder="Quantité"><br/>
                <div style="color:red"><span>@error('quantity'){{$message}}@enderror</span></div>
                <textarea id="desc" name="description"></textarea><br/>
                <textarea placeholder="Description" value="{! $article->descriptions !}" id="description">{!! $article->descriptions !!}</textarea><br/>
                <div class="counter_sell"><i id="number">1000</i></div><br/>
                <input type="submit" name="Submit" class="articlesubmit" value="Modifier">
            </form>
        </div>
    </div>
    <div class="pub-box">
            <div class="pub"><img src="{{ asset('../images/ecom.png') }}"/></div>
            <div class="pub"><img src="{{ asset('../images/pub_ecom.jpg') }}"/></div>
        </div>
    </div>
</div>
@include('templates/footer')