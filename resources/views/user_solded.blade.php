
<!DOCTYPE html!>
<html lang="ja">
<head>
    <title>Kaibai</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/css/style.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/media.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="assets/css/page.css" rel="stylesheet" type="text/css" media="screen" />
    <link rel="stylesheet" type="text/css"  href="assets/font/css/fontawesome.min.css">
    <link rel="stylesheet" type="text/css"  href="assets/font/css/all.min.css">
    <script type="text/javascript" src="assets/jquery.min.js"></script>
    <script type="text/javascript" src="assets/js/script.js"></script>
    <script type="text/javascript" src="assets/js/page.js"></script>
</head>
<body>
    <header>
        <div class="nav">
            <!-- <div class="bar"><i class="icon fa fa-bars fa-fw fa-lg" aria-hidden="true"></i></div> -->
            <a class="logo" href=""><img class="logo-image" src="assets/images/logo.png"/></a></span>
            <form class="search" action="#" method="get">
                <input name="research" placeholder="Search" type="text">
                <button type="submit" class="submitbtn" value="search"><i class="fas fa-search" aria-hidden="true"></i></button>
            </form>

        </div><!-- end header -->
        <div class="navbar">
            <div class="wrap-buttons">
                <div class="navbar-button m-b-header"><a href="#"><i><img src="assets/images/icon/2631151.png"></i><i>ホーム</i></a></div>
                <div class="navbar-button">
                    <a href="#"><i><img src="assets/images/icon/1141964.png"></i><i>カテゴリー</i></a>
                    <div class="dropdown">
                        <a href="#">Link 1</a>
                        <a href="#">Link 2</a>
                        <a href="#">Link 3</a>
                    </div>
                </div>
                <div class="navbar-button">
                    <a href="#"><i><img src="assets/images/icon/287623.png"></i><i>ショップ</i></a>
                    <div class="dropdown">
                        <a href="#">Link 1</a>
                        <a href="#">Link 2</a>
                    </div>
                </div>
                <div class="navbar-button m-b-header"><a href="#"><i><img src="assets/images/icon/1828940.png"></i><i>ヘルプ</i></a></div>
                <div class="navbar-button"><a href="#"><i><img src="assets/images/icon/2571195.png"></i><i>コンタクト</i></a></div>
                <div class="navbar-button">
                    <a href="#"><i><img src="assets/images/icon/1946429.png"></i><i>マイアカウント</i></a>
                    <div class="dropdown">
                        <a href="#">Link 1</a>
                        <a href="#">Link 2</a>
                    </div>
                </div>
                <div class="navbar-button"><a href="#"><i><img src="assets/images/icon/833314.png"></i><i>カート</i></a></div>
            </div>
        </div>
    </header>    
<div class="contain">
    <div class="stock-content">
        <br/>
        <div class="u-inf">
            <div class="icon-user">
                <i class="fas fa-user-circle fa-8x"></i>
                <strong class="user-name"></strong>
            </div>
            <div class="user-info">
                <span>Point: </span><strong>50pt</strong>
            </div>
        </div>
        
        <div class="side-nav">
            <ul>
            <li><a href="">Add article</a></li>
            <li><a href="">Products list</a></li>
            <li><a href="">View purchased</a></li>
            </ul>
        </div>
        <div class="loader" role="status" area-hidden="true" style="display: none; position:fixed">
            <img src="../assets/images/load/ico-loading.gif">
            <span class="sr-only">loading...</span>
        </div>
        <div class="solded" id="sld-table">
            <h2>Solded</h2>
            <table class="tab">
                <tr>
                    <th>Image</th>
                    <th><a class="col-sort-solded" id="sort-name" data-order="desc" href="">Articles</a></th>
                    <th>Date</th>
                    <th><a class="col-sort-solded" id="sort-size" data-order="desc" href="">Sizes</a></th>
                    <th><a class="col-sort-solded" id="sort-color" data-order="desc" href="">Color</a></th>
                    <th><a class="col-sort-solded" id="sort-quantity" data-order="desc" href="">Quantity</a></th>
                    <th><a class="col-sort-solded" id="sort-price" data-order="desc" href="">prix unitaire (yen)</a></th>
                    <th><a class="col-sort-solded" id="sort-charge" data-order="desc" href="">post charge</a></th>
                    <th><a class="col-sort-solded" id="sort-total" data-order="desc" href="">total</a></th>
                    <th><a class="col-sort-solded" id="sort-total-post" data-order="desc" href="">total<br>(with post charge)</a></th>
                    <th>Delete</th>
                </tr>
                <!-- tr -->
                <tr>
                    <td><img class="img-cart" src="assets/images/big/9ea375109c1eb570b9252976bf0e3d31.jpg"></td>
                    <td>Chossure</td>
                    <td>13-04-2021</td>
                    <td>50</td>
                    <td>red</td>
                    <td>10</td>
                    <td>10000 ¥</td>
                    <td>30 ¥</td>
                    <td>100000 ¥</td>
                    <td>100300 ¥</td>
                    <td><a class="del-v" href=""><button class="tab-v"  name="action" value="delete"><i class="fas fa-times"></i></button></td>
                </tr>
                <!-- /tr -->
                <!-- tr -->
                <tr>
                    <td><img class="img-cart" src="assets/images/big/9ea375109c1eb570b9252976bf0e3d31.jpg"></td>
                    <td>13-04-2021</td>
                    <td>Chossure</td>
                    <td>50</td>
                    <td>red</td>
                    <td>10</td>
                    <td>10000 ¥</td>
                    <td>30 ¥</td>
                    <td>100000 ¥</td>
                    <td>100300 ¥</td>
                    <td><a class="del-v" href=""><button class="tab-v"  name="action" value="delete"><i class="fas fa-times"></i></button></td>
                </tr>
                <!-- /tr -->
                <!-- tr -->
                <tr>
                    <td><img class="img-cart" src="assets/images/big/9ea375109c1eb570b9252976bf0e3d31.jpg"></td>
                    <td>13-04-2021</td>
                    <td>Chossure</td>
                    <td>50</td>
                    <td>red</td>
                    <td>10</td>
                    <td>10000 ¥</td>
                    <td>30 ¥</td>
                    <td>100000 ¥</td>
                    <td>100300 ¥</td>
                    <td><a class="del-v" href=""><button class="tab-v"  name="action" value="delete"><i class="fas fa-times"></i></button></td>
                </tr>
                <!-- /tr -->
                <!-- tr -->
                <tr>
                    <td><img class="img-cart" src="assets/images/big/9ea375109c1eb570b9252976bf0e3d31.jpg"></td>
                    <td>13-04-2021</td>
                    <td>Chossure</td>
                    <td>50</td>
                    <td>red</td>
                    <td>10</td>
                    <td>10000 ¥</td>
                    <td>30 ¥</td>
                    <td>100000 ¥</td>
                    <td>100300 ¥</td>
                    <td><a class="del-v" href=""><button class="tab-v"  name="action" value="delete"><i class="fas fa-times"></i></button></td>
                </tr>
                <!-- /tr -->
                <!-- tr -->
                <tr>
                    <td><img class="img-cart" src="assets/images/big/9ea375109c1eb570b9252976bf0e3d31.jpg"></td>
                    <td>13-04-2021</td>
                    <td>Chossure</td>
                    <td>50</td>
                    <td>red</td>
                    <td>10</td>
                    <td>10000 ¥</td>
                    <td>30 ¥</td>
                    <td>100000 ¥</td>
                    <td>100300 ¥</td>
                    <td><a class="del-v" href=""><button class="tab-v"  name="action" value="delete"><i class="fas fa-times"></i></button></td>
                </tr>
                <!-- /tr -->
                <!-- tr -->
                <tr>
                    <td><img class="img-cart" src="assets/images/big/9ea375109c1eb570b9252976bf0e3d31.jpg"></td>
                    <td>13-04-2021</td>
                    <td>Chossure</td>
                    <td>50</td>
                    <td>red</td>
                    <td>10</td>
                    <td>10000 ¥</td>
                    <td>30 ¥</td>
                    <td>100000 ¥</td>
                    <td>100300 ¥</td>
                    <td><a class="del-v" href=""><button class="tab-v"  name="action" value="delete"><i class="fas fa-times"></i></button></td>
                </tr>
                <!-- /tr -->
                <!-- tr -->
                <tr>
                    <td><img class="img-cart" src="assets/images/big/9ea375109c1eb570b9252976bf0e3d31.jpg"></td>
                    <td>13-04-2021</td>
                    <td>Chossure</td>
                    <td>50</td>
                    <td>red</td>
                    <td>10</td>
                    <td>10000 ¥</td>
                    <td>30 ¥</td>
                    <td>100000 ¥</td>
                    <td>100300 ¥</td>
                    <td><a class="del-v" href=""><button class="tab-v"  name="action" value="delete"><i class="fas fa-times"></i></button></td>
                </tr>
                <!-- /tr -->
                <!-- tr -->
                <tr>
                    <td><img class="img-cart" src="assets/images/big/9ea375109c1eb570b9252976bf0e3d31.jpg"></td>
                    <td>13-04-2021</td>
                    <td>Chossure</td>
                    <td>50</td>
                    <td>red</td>
                    <td>10</td>
                    <td>10000 ¥</td>
                    <td>30 ¥</td>
                    <td>100000 ¥</td>
                    <td>100300 ¥</td>
                    <td><a class="del-v" href=""><button class="tab-v"  name="action" value="delete"><i class="fas fa-times"></i></button></td>
                </tr>
                <!-- /tr -->
                <!-- tr -->
                <tr>
                    <td><img class="img-cart" src="assets/images/big/9ea375109c1eb570b9252976bf0e3d31.jpg"></td>
                    <td>13-04-2021</td>
                    <td>Chossure</td>
                    <td>50</td>
                    <td>red</td>
                    <td>10</td>
                    <td>10000 ¥</td>
                    <td>30 ¥</td>
                    <td>100000 ¥</td>
                    <td>100300 ¥</td>
                    <td><a class="del-v" href=""><button class="tab-v"  name="action" value="delete"><i class="fas fa-times"></i></button></td>
                </tr>
                <!-- /tr -->
            </table><br/>
            
            <a href=""><button class="t_btn"><i>< </i></button></a>
            
            
                
                <i>1</i>
            
                <a href=""><button class="t_btn"><i>2</i></button></a>
            
                <a href=""><button class="t_btn"><i>> </i></button></a>
            
        </div>
    </div>
</div>
<footer class="u-clearfix u-footer u-grey-80" id="sec-2b66"><div class="u-clearfix u-sheet u-sheet-1">
    <img class="u-image u-image-default u-image-1" src="assets/images/logo_footer.png" alt="" data-image-width="350" data-image-height="226">
    <div class="u-align-left u-social-icons u-spacing-10 u-social-icons-1">
      <a class="u-social-url" title="facebook" target="_blank" href=""><span class="u-icon u-social-facebook u-social-icon u-icon-1"><svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 112 112" style=""><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-af85"></use></svg><svg class="u-svg-content" viewBox="0 0 112 112" x="0" y="0" id="svg-af85"><circle fill="currentColor" cx="56.1" cy="56.1" r="55"></circle><path fill="#FFFFFF" d="M73.5,31.6h-9.1c-1.4,0-3.6,0.8-3.6,3.9v8.5h12.6L72,58.3H60.8v40.8H43.9V58.3h-8V43.9h8v-9.2
        c0-6.7,3.1-17,17-17h12.5v13.9H73.5z"></path></svg></span>
      </a>
      <a class="u-social-url" title="twitter" target="_blank" href=""><span class="u-icon u-social-icon u-social-twitter u-icon-2"><svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 112 112" style=""><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-e225"></use></svg><svg class="u-svg-content" viewBox="0 0 112 112" x="0" y="0" id="svg-e225"><circle fill="currentColor" class="st0" cx="56.1" cy="56.1" r="55"></circle><path fill="#FFFFFF" d="M83.8,47.3c0,0.6,0,1.2,0,1.7c0,17.7-13.5,38.2-38.2,38.2C38,87.2,31,85,25,81.2c1,0.1,2.1,0.2,3.2,0.2
        c6.3,0,12.1-2.1,16.7-5.7c-5.9-0.1-10.8-4-12.5-9.3c0.8,0.2,1.7,0.2,2.5,0.2c1.2,0,2.4-0.2,3.5-0.5c-6.1-1.2-10.8-6.7-10.8-13.1
        c0-0.1,0-0.1,0-0.2c1.8,1,3.9,1.6,6.1,1.7c-3.6-2.4-6-6.5-6-11.2c0-2.5,0.7-4.8,1.8-6.7c6.6,8.1,16.5,13.5,27.6,14
        c-0.2-1-0.3-2-0.3-3.1c0-7.4,6-13.4,13.4-13.4c3.9,0,7.3,1.6,9.8,4.2c3.1-0.6,5.9-1.7,8.5-3.3c-1,3.1-3.1,5.8-5.9,7.4
        c2.7-0.3,5.3-1,7.7-2.1C88.7,43,86.4,45.4,83.8,47.3z"></path></svg></span>
      </a>
      <a class="u-social-url" title="instagram" target="_blank" href=""><span class="u-icon u-social-icon u-social-instagram u-icon-3"><svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 112 112" style=""><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-5e83"></use></svg><svg class="u-svg-content" viewBox="0 0 112 112" x="0" y="0" id="svg-5e83"><circle fill="currentColor" cx="56.1" cy="56.1" r="55"></circle><path fill="#FFFFFF" d="M55.9,38.2c-9.9,0-17.9,8-17.9,17.9C38,66,46,74,55.9,74c9.9,0,17.9-8,17.9-17.9C73.8,46.2,65.8,38.2,55.9,38.2
        z M55.9,66.4c-5.7,0-10.3-4.6-10.3-10.3c-0.1-5.7,4.6-10.3,10.3-10.3c5.7,0,10.3,4.6,10.3,10.3C66.2,61.8,61.6,66.4,55.9,66.4z"></path><path fill="#FFFFFF" d="M74.3,33.5c-2.3,0-4.2,1.9-4.2,4.2s1.9,4.2,4.2,4.2s4.2-1.9,4.2-4.2S76.6,33.5,74.3,33.5z"></path><path fill="#FFFFFF" d="M73.1,21.3H38.6c-9.7,0-17.5,7.9-17.5,17.5v34.5c0,9.7,7.9,17.6,17.5,17.6h34.5c9.7,0,17.5-7.9,17.5-17.5V38.8
        C90.6,29.1,82.7,21.3,73.1,21.3z M83,73.3c0,5.5-4.5,9.9-9.9,9.9H38.6c-5.5,0-9.9-4.5-9.9-9.9V38.8c0-5.5,4.5-9.9,9.9-9.9h34.5
        c5.5,0,9.9,4.5,9.9,9.9V73.3z"></path></svg></span>
      </a>
      <a class="u-social-url" title="linkedin" target="_blank" href=""><span class="u-icon u-social-icon u-social-linkedin u-icon-4"><svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 112 112" style=""><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-4eae"></use></svg><svg class="u-svg-content" viewBox="0 0 112 112" x="0" y="0" id="svg-4eae"><circle fill="currentColor" cx="56.1" cy="56.1" r="55"></circle><path fill="#FFFFFF" d="M41.3,83.7H27.9V43.4h13.4V83.7z M34.6,37.9L34.6,37.9c-4.6,0-7.5-3.1-7.5-7c0-4,3-7,7.6-7s7.4,3,7.5,7
        C42.2,34.8,39.2,37.9,34.6,37.9z M89.6,83.7H76.2V62.2c0-5.4-1.9-9.1-6.8-9.1c-3.7,0-5.9,2.5-6.9,4.9c-0.4,0.9-0.4,2.1-0.4,3.3v22.5
        H48.7c0,0,0.2-36.5,0-40.3h13.4v5.7c1.8-2.7,5-6.7,12.1-6.7c8.8,0,15.4,5.8,15.4,18.1V83.7z"></path></svg></span>
      </a>
    </div>
  </div>
</footer>  
</body>
</html>