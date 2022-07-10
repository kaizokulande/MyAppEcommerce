<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;
class Shop_stock extends Model
{
    use HasFactory;
    /* uploaded articles */
    public static function get_count_articles($id_shop){
        $sql="SELECT COUNT(*) AS total FROM articles_shop WHERE id_shop='%s'";
        $sql = sprintf($sql,$id_shop);
        $count = collect(\DB::select($sql))->first();
        return $count;
    }

    public static function get_articles($order,$col_name,$id_shop,$depart,$articles_per_page){
        $sql="SELECT * FROM articles_shop WHERE id_shop = '%s' ORDER BY %s %s LIMIT %s,%s";
        $articles = DB::select(sprintf($sql,$id_shop,$col_name,$order,$depart,$articles_per_page));
        return $articles;
    }
    public static function get_article($id_shop,$id_article){
        $sql="SELECT * FROM articles_shop WHERE id_shop = '%s' AND id_article = '%s'";
        $article = collect(DB::select(sprintf($sql,$id_shop,$id_article)))->first();
        return $article;
    }
    public static function update_quantity($id_shop,$id_article,$new_quantity,$price){
        $sql= "UPDATE articles SET quantity='%s',total_price=calctotal('%s','%s')  WHERE id_shop='%s' AND id_article='%s'";
        $sql = DB::update(sprintf($sql,$new_quantity,$price,$new_quantity,$id_shop,$id_article));
    }
    public static function delete_article($id_shop,$id_article){
        $sql = "UPDATE articles SET states='deleted',delete_date=now()  WHERE id_shop='%s' AND id_article='%s'";
        $sql = DB::update(sprintf($sql,$id_shop,$id_article));
    }
    /* solded */
    public static function count_s_articles($id_shop){
        $sql="SELECT COUNT(*) AS total FROM solded_articles_shop WHERE id_shop='%s'";
        $sql = sprintf($sql,$id_shop);
        $count = collect(\DB::select($sql))->first();
        return $count;
    }
    public static function get_s_articles($order,$col_name,$id_shop,$depart,$articles_per_page){
        $sql="SELECT * FROM solded_articles_shop WHERE id_shop = '%s' ORDER BY %s %s LIMIT %s,%s";
        $articles = DB::select(sprintf($sql,$id_shop,$col_name,$order,$depart,$articles_per_page));
        return $articles;
    }
    public static function get_s_article($id_shop,$id_article){
        $sql="SELECT * FROM solded_articles_shop WHERE id_shop = '%s' AND id_article = '%s'";
        $article = collect(DB::select(sprintf($sql,$id_shop,$id_article)))->first();
        return $article;
    }
    public static function delete_s_article($id_shop,$id_article){
        $sql = "UPDATE solded_articles SET states='deleted' WHERE id_shop='%s' AND id_article='%s'";
        $sql = DB::update(sprintf($sql,$id_shop,$id_article));
    }
    /* uploaded articles OUTPUT */
    public static function order_articles($id_shop,$shop_name,$order,$col_name,$request_col_name,$depart,$articles_per_page,$current_page,$total_page){
        if($order!='desc' && $order!='asc'){
            $order = 'desc';
        }
        $sql="SELECT * FROM articles_shop WHERE states!='deleted' AND id_shop ='%s' ORDER BY %s %s LIMIT %s,%s";
        $articles = DB::select(sprintf($sql,$id_shop,$col_name,$order,$depart,$articles_per_page));
        $new_order='';
        if($order == 'desc'){
            $new_order = 'asc';
        }else{
            $new_order = 'desc';
        }
        $output = '';
        $output .= '
            <table class="tab">
            <tr>
                <th>Picture</th>
                <th><a class="col-sort" id="sort-name" data-order="'.$new_order.'" href="'.route('sort-shop',['page'=>$current_page]).'">Articles</a></th>
                <th><a class="col-sort" id="sort-size" data-order="'.$new_order.'" href="'.route('sort-shop',['page'=>$current_page]).'">sizes</a></th>
                <th><a class="col-sort" id="sort-color" data-order="'.$new_order.'" href="'.route('sort-shop',['page'=>$current_page]).'">Color</a></th>
                <th><a class="col-sort" id="sort-quantity" data-order="'.$new_order.'" href="'.route('sort-shop',['page'=>$current_page]).'">Quantity</a></th>
                <th><a class="col-sort" id="sort-price" data-order="'.$new_order.'" href="'.route('sort-shop',['page'=>$current_page]).'">Prix yen</a></th>
                <th>Update</th>
                <th><a class="col-sort" id="sort-total" data-order="'.$new_order.'" href="'.route('sort-shop',['page'=>$current_page]).'">Total</a></th>
            </tr>';
        foreach($articles as $art){
            $output .='
            <tr>
                <td><a href="up_article/'.$art->id_article.'/'.$art->article_name.'"><img class="img-cart" src="'.asset($art->small_images).'"></a></td>
                <td><a href="up_article/'.$art->id_article.'/'.$art->article_name.'">'.$art->article_name.'</a></td>
                <td>'.$art->sizes.'</td>
                <td>'.$art->color.'</td>
                <td id="quantity">'.$art->quantity.'</td>
                <td id="price">'.number_format($art->price).'¥</td>
                <td>
                    <a class="minus" href="'.route('update_shop_article', ['action'=>'minus','article'=>$art->id_article]).'">
                        <button><i class="fas fa-minus"></i></button>
                    </a>
                    <input id="quantity" name="quantity" placeholder="1" type="number" style="max-width:50px">
                    <a class="plus" href="'.route('update_shop_article', ['action'=>'plus','article'=>$art->id_article]).'">
                        <button><i class="fas fa-plus"></i></button>
                    </a>
                    <a class="del" href="'.route('delete_shop_article', ['article'=>$art->id_article,'name'=>$art->article_name]).'">
                        <button class="tab-delete"><i class="fas fa-times"></i></button>
                    </a>
                </td>
                <td id="total">'.number_format($art->total_price).' ¥</td>
            </tr>';
        }
        $output .= '</table><br/>
            <div class="page-list">';
            $prev_page = $current_page-1;
			$next_page = $current_page+1;
                if ($total_page>1){
                    if($prev_page > 0){
                        $output .='<div class="page-surf"><a href="/'.$shop_name.'/'.'stock/'.$prev_page.'/'.$order.'/'.$request_col_name.'"><button><< </button></a></div>';
                    }
                    for($page = 1;$page<=$total_page;$page++){
                        if($current_page == $page){
                            $output .='<div class="page-white">'.$page.'</div>';
                        }else{
                            $output .='<div class="page"><a href="/'.$shop_name.'/'.'stock/'.$page.'/'.$order.'/'.$request_col_name.'"><button>'.$page.' </button></a></div>';
                        }
                    }
                    if($next_page<=$total_page){
                        $output .='<div class="page-surf"><a href="/'.$shop_name.'/'.'stock/'.$next_page.'/'.$order.'/'.$request_col_name.'"><button>>> </button></a></div>';
                    }
                }
            $output .='</div>';
        return $output;
    }

     /* solded articles OUTPUT */
     public static function order_s_articles($id_shop,$shop_name,$order,$col_name,$request_col_name,$depart,$articles_per_page,$current_page,$total_page){
        $sql="SELECT * FROM solded_articles_shop WHERE id_shop ='%s' ORDER BY %s %s LIMIT %s,%s";
        $articles = DB::select(sprintf($sql,$id_shop,$col_name,$order,$depart,$articles_per_page));
        $new_order='';
        if($order == 'desc'){
            $new_order = 'asc';
        }else{
            $new_order = 'desc';
        }
        $output = '';
        $output .= '
            <table class="tab">
            <tr>
                <th><a class="col-sort" id="sort-sdate" data-order="'.$new_order.'" href="'.route('sort-shop-solded',['page'=>$current_page]).'">Dates</a></th>
                <th>Picture</th>
                <th><a class="col-sort" id="sort-name" data-order="'.$new_order.'" href="'.route('sort-shop-solded',['page'=>$current_page]).'">Articles</a></th>
                <th><a class="col-sort" id="sort-size" data-order="'.$new_order.'" href="'.route('sort-shop-solded',['page'=>$current_page]).'">sizes</a></th>
                <th><a class="col-sort" id="sort-color" data-order="'.$new_order.'" href="'.route('sort-shop-solded',['page'=>$current_page]).'">Color</a></th>
                <th><a class="col-sort" id="sort-quantity" data-order="'.$new_order.'" href="'.route('sort-shop-solded',['page'=>$current_page]).'">Quantity</a></th>
                <th><a class="col-sort" id="sort-price" data-order="'.$new_order.'" href="'.route('sort-shop-solded',['page'=>$current_page]).'">Prix yen</a></th>
                <th>Delete</th>
                <th><a class="col-sort" id="sort-total" data-order="'.$new_order.'" href="'.route('sort-shop-solded',['page'=>$current_page]).'">Total</a></th>
            </tr>';
        foreach($articles as $art){
            $output .='
            <tr>
                <td>'.$art->dates.'</td>
                <td><img class="img-cart" src="'.asset($art->small_images).'"></td>
                <td>'.$art->article_name.'</td>
                <td>'.$art->sizes.'</td>
                <td>'.$art->color.'</td>
                <td id="quantity">'.$art->quantity.'</td>
                <td id="price">'.number_format($art->price).'¥</td>
                <td>
                    <a class="del" href="'.route('delete_solded_article', ['article'=>$art->id_article]).'">
                        <button class="tab-delete"><i class="fas fa-times"></i></button>
                    </a>
                </td>
                <td id="total">'.number_format($art->total_price).' ¥</td>
            </tr>';
        }
        $output .= '</table><br/>
            <div class="page-list">';
            $prev_page = $current_page-1;
			$next_page = $current_page+1;
                if ($total_page>1){
                    if($prev_page > 0){
                        $output .='<div class="page-surf"><a href="/'.$shop_name.'/'.'shop_solded/'.$prev_page.'/'.$order.'/'.$request_col_name.'"><button><< </button></a></div>';
                    }
                    for($page = 1;$page<=$total_page;$page++){
                        if($current_page == $page){
                            $output .='<div class="page-white">'.$page.'</div>';
                        }else{
                            $output .='<div class="page"><a href="/'.$shop_name.'/'.'shop_solded/'.$page.'/'.$order.'/'.$request_col_name.'"><button>'.$page.' </button></a></div>';
                        }
                    }
                    if($next_page<=$total_page){
                        $output .='<div class="page-surf"><a href="/'.$shop_name.'/'.'shop_solded/'.$next_page.'/'.$order.'/'.$request_col_name.'"><button>>> </button></a></div>';
                    }
                }
            $output .='</div>';
        return $output;
    }
}
