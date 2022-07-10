<?php
    namespace App\Http\Controllers;
    $categories = Base_controller::getCategories();
    ?>
@include('templates/header')
<div class="contain">
    <div class="content">
    <div class="article_upload">
        <h4>informations</h4>
        <p>商品アップデートする。</p>
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
                    <input type="text" name="name" value="{{ $article->article_name }}" placeholder="Article Name"><br/>
                    <div style="color:red"><span>@error('name'){{$message}}@enderror</span></div>
                </div>
                <div class="input-price">
                    <span>¥</span>
                    <input type="numeric" id="art_price" value="{{ $article->price }}" name="price" placeholder="Article Price"><br/>
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
                <input type="number" value="{{ $article->quantity }}"name="quantity" id="quantity" placeholder="quantity"><br/>
                <div style="color:red"><span>@error('quantity'){{$message}}@enderror</span></div>
                <textarea id="desc" name="description"></textarea><br/>
                <textarea placeholder="article description" value="{! $article->descriptions !}" id="description">{!! $article->descriptions !!}</textarea><br/>
                <div class="counter_sell"><i id="number">1000</i></div><br/>
                <input type="submit" name="Submit" class="articlesubmit" value="アップデート">
            </form>
        </div>
    </div>
    <div class="pub-box">
            <div class="pub"></div>
            <div class="pub"></div>
        </div>
    </div>
</div>
@include('templates/footer')