<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
class Base_controller extends Controller
{
    public static function getCategories(){
        $categories = DB::select("SELECT * FROM fr_categories ORDER BY categorie DESC");
        return $categories;
    }
    public static function getCartarticlequantity(){
        $cart_quantity = DB::table('cart')->where('id_user',Auth::id())->sum('quantity');
        return $cart_quantity;
    }
    public static function get_colors(){
        $colors = DB::table('colors')->get();
        return $colors;
    }

    function view_login(){
        return view('login');
    }
    function view_sellarticle(){
        $data['categories'] = $this->getCategories();
        return view('sell_article',$data);
    }
    function view_register(){
        return view('register');
    }
    function get_logo_user($gender){
        $logo = "../images/kaibai_h.png";
        if($gender=="Feminin"){
            $logo = "../images/kaibai_f.png";
        }
        return $logo;
    }
    function small_folders($shop_name){
        if($shop_name==null){
            $path = 'images/small/';
            if(!file_exists($path)){
                mkdir($path,755);
            }
        }else{
            $path = 'images/shops/'. $shop_name. '/'.'small/';
            if(!file_exists("images/shops/".$shop_name.'/')){
                mkdir("images/shops/".$shop_name.'/');
                mkdir($path,755);
            }else if(!file_exists($path)){
                mkdir($path,755);
            }
        }
        return $path;
    }

    function big_folders($shop_name){
        if($shop_name==null){
            $path = 'images/big/';
            if(!file_exists($path)){
                mkdir($path,755);
            }
        }else{
            $path = 'images/shops/'. $shop_name. '/'.'big/';
            if(!file_exists($path)){
                if(!file_exists("images/shops/".$shop_name.'/')){
                    mkdir("images/shops/".$shop_name.'/');
                    mkdir($path,755);
                }else if(!file_exists($path)){
                    mkdir($path,755);
                }
            }
        }
        return $path;
    }

    /* image uplaod */
    function image_upload($imgfile,$big_path,$small_path){
        if($imgfile != NULL){
            $image_resize = Image::make($imgfile->getRealPath());
            $img_height = $image_resize->height();
            $img_width = $image_resize->width();
            $width = 0;
            $height = 0;
            $x_axis = 0;
            $y_axis = 0;
            if($img_height<$img_width){
                $width = $img_height;
                $height = $img_height;
                $x_axis = ceil(($img_width - $img_height) /2);
            }else{
                $height = $img_width;
                $width = $img_width;
                $y_axis = ceil(($img_height - $img_width) / 2);
            }

            
            $image_resize->crop($width,$height,$x_axis,$y_axis);
            $image_resize->save($big_path);
            $image_resize->resize(200,200);
            $image_resize->save($small_path);
        }
     }

    function calctotal($quantity,$price){
        $total = 0;
        if($quantity>0){
            $total = $quantity*$price;
        }
        return $total;
    }

    /* add space db */
    function space_nl($text){
        $text = preg_replace("#\[sp\]#","&nbsp;",$text);
		$text = preg_replace("#\[nl\]#","<br/>\n",$text);
		return $text;
    }
    function br_space_to_normal($text){
        $text = Str::replace('&nbsp;', ' ', $text);
        $text = Str::replace('<br/>', ' ', $text);
        return $text;
    }

    /* get column name of table in stock */
    function get_col_name($col){
        if($col=='sort-name') return "article_name";
        if($col == 'sort-size') return "sizes";
        if($col == 'sort-color') return "color";
        if($col == 'sort-quantity') return "quantity";
        if($col == 'sort-price') return "price";
        if($col == 'sort-total') return "total_price";
        if($col == 'sort-total-post') return "total_with_post";
        if($col == 'sort-pdate') return "purchase_dates";
        if($col == 'sort-sdate') return "dates";
        else return 'creation_date';
    }

    /* delete coma */
    function get_price($price){
        $price = intval(str_replace(',','.',$price));
        return $price;
    }

    
    function page_and_orders($order,$page){
        $orderpage = array();
        if(isset($order) && !empty($order)){
            if($order!=='desc' &&  $order!=='asc'){
                $order='desc';
            }
            $orderpage['order'] = $order;
        }else{
            $orderpage['order'] = 'desc';
        }
		if(isset($page) && !empty($page) && $page>0){
			$page =intval($page);
			$orderpage['currentpage'] = $page;
		}else{
			$orderpage['currentpage']=1;
        }
        return $orderpage;
    }

    function sort_colname($col_name, $given_colname){
        if(isset($col_name) && !empty($col_name)){
            return $col_name;
        }else{
            return $given_colname;
        }
    }
    
}
