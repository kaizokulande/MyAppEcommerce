@include('templates/shop_header') 

    <div class="shop_products">
        <h2>設定</h2>
        <div class="setting">
            <div class="grid-content">
                <div class="grid-item"><span>Shop name:</span></div>
                <div class="grid-item" ><span id="sp_name">{{ $shop->shop_name }}</span></div>
                <div class="grid-item"><button onclick="show_name()"><i class="fas fa-pen"></i>Modify</button></div>
            </div>
            <div class="change" id="change_name">
                <form id="form_name" method="post" action="/shop/change_name">@csrf
                    <span id="success_name"></span><br/>
                    <div class="search_box">
                        <input name="shop_name" value="{{ $shop->shop_name }}" class="change-input" type="text" placeholder="Shop name">
                        <input class="search_box" id="submit_name" type="submit" value="Change">
                    </div>
                    @if(session('name_success'))
                        <span id="success_message">{{session('name_success')}}</span><br/>
                    @endif
                    @if(session('name_error'))
                        <span style="color:red">{{session('name_error')}}</span><br/>
                    @endif
                </form>
            </div><br/><hr/>
            <div class="grid-content">
                <div class="grid-item">管理者:</div>
                <div class="grid-item">管理者を設定</div>
                <div class="grid-item"><button onclick="show_admins()"><i class="fas fa-pen"></i>Modify</button></div>
            </div>
            <div class="change" id="change_admins">
                <div class="admin-list">
                    @if (count($admins)>0)
                        <p>管理者を削除出来るのはショップを作成した会員だけです。</p>
                    @endif
                    <ul>
                        @foreach ($admins as $adm)
                            <li><div class="admin-names">
                                <span>{{$adm->firstname}} {{$adm->lastname}}</span>
                                @can('isShopSuperAdmin')
                                    <span><a id="del_adm" href="/shop/del_adm/{{$adm->id}}">&times;</a></span>
                                @endcan
                            </div></li>
                        @endforeach
                    </ul>    
                </div>
                <span id="admin_success"></span><br/>
                <form autocomplete="off" id="form_web" method="post" action="/shop/add_admin">@csrf
                    <div class="search_box relpos">
                        <div class="autocomplete" id="autocomplete">
                            <input id="input-add" class="change-input" type="text" name="email">
                            <input type="submit" value="Add">
                        </div>
                    </div><br/>
                    @if(session('add_success'))
                        <span id="success_message">{{session('add_success')}}</span><br/>
                    @endif
                    @if(session('add_error'))
                        <span id="add_admin_error" style="color:red">{{session('add_error')}}</span><br/>
                    @endif
                </form>
            </div><br/><hr/>
            <div class="grid-content">
                <div class="grid-item">Email:</div>
                <div class="grid-item" id="sp_email">{{$shop->shop_email}}</div>
                <div class="grid-item"><button onclick="show_email()"><i class="fas fa-pen"></i>Modify</button></div>
            </div>
            <div class="change" id="change_email">
                <span id="email_success"></span><br/>
                <form id="form_email" method="post" action="/shop/change_email">@csrf
                    <span id="success_email"></span><br/>
                    <div class="search_box">
						<input class="change-input" value="{{$shop->shop_email}}" placeholder="New email" type="text" name="email"><br/>
                    </div>
                    <span id="email_error" style="color:red"></span><br/>
                    <div class="search_box">
                        <input class="change-input" placeholder="Confirm email" type="text" name="email_confirmation"><br/>
                    </div>
                    <span id="cemail_error" style="color:red"></span><br/>
                    <div class="search_box">
                        <input type="submit" value="Change">
                    </div>
                    @if(session('email_success'))
                        <span id="success_message">{{session('email_success')}}</span><br/>
                    @endif
                    @if(session('email_error'))
                        <span style="color:red">{{session('email_error')}}</span><br/>
                    @endif
                </form>
            </div><br/><hr/>
            <div class="grid-content">
                <div class="grid-item">Phone Number:</div>
                <div class="grid-item" id="sp_phone">{{$shop->phone_number}}</div>
                <div class="grid-item"><button onclick="show_phone()"><i class="fas fa-pen"></i>Modify</button></div>
            </div>
            <div class="change" id="change_phone">
                <span id="phone_success"></span><br/>
                <form id="form_phone" method="post" action="/shop/change_phone">@csrf
                    <div class="search_box">
                        <input class="change-input" value="{{$shop->phone_number}}" placeholder="Phone number" type="text" name="shop_phone">
                        <input type="submit" value="Change">
                    </div>
                    @if(session('phone_success'))
                        <span id="success_message">{{session('phone_success')}}</span><br/>
                    @endif
                    @if(session('phone_error'))
                        <span style="color:red">{{session('phone_error')}}</span><br/>
                    @endif
                </form>
            </div><br/><hr/>
            <div class="grid-content">
                <div class="grid-item">Web link:</div>
                <div class="grid-item" id="sp_site">{{Str::limit($shop->shop_site,32,'...')}}</div>
                <div class="grid-item"><button onclick="show_web()"><i class="fas fa-pen"></i>Modify</button></div>
            </div>
            <div class="change" id="change_web">
                <span id="web_success"></span><br/>
                <form id="form_web" method="post" action="/shop/change_link">@csrf
                    <div class="search_box">
                        <input class="change-input" value="{{$shop->shop_site}}" type="text" name="new_web">
                        <input type="submit" value="Change">
                    </div>
                    @if(session('web_success'))
                        <span id="success_message">{{session('web_success')}}</span><br/>
                    @endif
                    @if(session('web_error'))
                        <span style="color:red">{{session('web_error')}}</span><br/>
                    @endif
                </form>
            </div><br/><hr/>
        </div>
    </div>
</div>