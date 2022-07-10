<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;
class Stock extends Model
{
    use HasFactory;
    /* uploaded articles */
    public static function get_count_articles($id){
        $sql="SELECT COUNT(*) AS total FROM articles_user WHERE id='%s' AND states!='deleted'";
        $sql = sprintf($sql,$id);
        $count = collect(\DB::select($sql))->first();
        return $count;
    }

    public static function get_articles($order,$col_name,$id,$depart,$articles_per_page){
        $sql="SELECT * FROM articles_user WHERE states!='deleted' AND id = '%s' ORDER BY %s %s LIMIT %s,%s";
        $articles = DB::select(sprintf($sql,$id,$col_name,$order,$depart,$articles_per_page));
        return $articles;
    }
    /* purchased */
    public static function count_p_articles($id){
        $sql="SELECT COUNT(*) AS total FROM purchased_user WHERE id='%s'";
        $sql = sprintf($sql,$id);
        $count = collect(\DB::select($sql))->first();
        return $count;
    }
    public static function get_p_articles($order,$col_name,$id,$depart,$articles_per_page){
        $sql="SELECT * FROM purchased_user WHERE id = '%s' ORDER BY %s %s LIMIT %s,%s";
        $articles = DB::select(sprintf($sql,$id,$col_name,$order,$depart,$articles_per_page));
        return $articles;
    }
    public static function get_purchased_delete($id_article){
        $sql="SELECT * FROM purchase WHERE id_article = '%s'";
        $article = collect(\DB::select(sprintf($sql,$id_article)))->first();
        return $article;
    }
    /* solded */
    public static function count_s_articles($id){
        $sql="SELECT COUNT(*) AS total FROM solded_articles_user WHERE id='%s'";
        $sql = sprintf($sql,$id);
        $count = collect(\DB::select($sql))->first();
        return $count;
    }
    public static function get_s_articles($order,$col_name,$id,$depart,$articles_per_page){
        $sql="SELECT * FROM solded_articles_user WHERE id = '%s' ORDER BY %s %s LIMIT %s,%s";
        $articles = DB::select(sprintf($sql,$id,$col_name,$order,$depart,$articles_per_page));
        return $articles;
    }
    public static function get_solded_delete($id_article){
        $sql="SELECT * FROM user_solded_articles WHERE id_article = '%s'";
        $article = collect(\DB::select(sprintf($sql,$id_article)))->first();
        return $article;
    }
    /* uploaded articles OUTPUT */
    public static function order_articles($id,$order,$col_name,$request_col_name,$depart,$articles_per_page,$current_page,$total_page){
        if($order!='desc' && $order!='asc'){
            $order = 'desc';
        }
        $sql="SELECT * FROM articles_user WHERE states!='deleted' AND id ='%s' ORDER BY %s %s LIMIT %s,%s";
        $articles = DB::select(sprintf($sql,$id,$col_name,$order,$depart,$articles_per_page));
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
                <th><a class="col-sort" id="sort-name" data-order="'.$new_order.'" href="'.route('sort',['page'=>$current_page]).'">Articles</a></th>
                <th><a class="col-sort" id="sort-size" data-order="'.$new_order.'" href="'.route('sort',['page'=>$current_page]).'">sizes</a></th>
                <th><a class="col-sort" id="sort-color" data-order="'.$new_order.'" href="'.route('sort',['page'=>$current_page]).'">Color</a></th>
                <th><a class="col-sort" id="sort-quantity" data-order="'.$new_order.'" href="'.route('sort',['page'=>$current_page]).'">Quantity</a></th>
                <th><a class="col-sort" id="sort-price" data-order="'.$new_order.'" href="'.route('sort',['page'=>$current_page]).'">Prix yen</a></th>
                <th>Update</th>
                <th><a class="col-sort" id="sort-total" data-order="'.$new_order.'" href="'.route('sort',['page'=>$current_page]).'">Total</a></th>
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
                    <a class="minus" href="'.route('update_article', ['action'=>'minus','article'=>$art->id_article]).'">
                        <button><i class="fas fa-minus"></i></button>
                    </a>
                    <input id="quantity" name="quantity" placeholder="1" type="number" style="max-width:50px">
                    <a class="plus" href="'.route('update_article', ['action'=>'plus','article'=>$art->id_article]).'">
                        <button><i class="fas fa-plus"></i></button>
                    </a>
                    <a class="del" href="'.route('delete_article', ['article'=>$art->id_article,'name'=>$art->article_name]).'">
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
                        $output .='<div class="page-surf"><a href="/stock/'.$prev_page.'/'.$order.'/'.$request_col_name.'"><button><< </button></a></div>';
                    }
                    for($page = 1;$page<=$total_page;$page++){
                        if($current_page == $page){
                            $output .='<div class="page-white">'.$page.'</div>';
                        }else{
                            $output .='<div class="page"><a href="/stock/'.$page.'/'.$order.'/'.$request_col_name.'"><button>'.$page.' </button></a></div>';
                        }
                    }
                    if($next_page<=$total_page){
                        $output .='<div class="page-surf"><a href="/stock/'.$next_page.'/'.$order.'/'.$request_col_name.'"><button>>> </button></a></div>';
                    }
                }
            $output .='</div>';
        return $output;
    }
    /* purchased articles OUTPUT */
    public static function order_purchased_articles($id,$order,$col_name,$request_col_name,$depart,$articles_per_page,$current_page,$total_page){
        if($order!='desc' && $order!='asc'){
            $order = 'desc';
        }
        $sql="SELECT * FROM purchased_user WHERE id ='%s' ORDER BY %s %s LIMIT %s,%s";
        $articles = DB::select(sprintf($sql,$id,$col_name,$order,$depart,$articles_per_page));
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
                <th><a class="col-sort" id="sort-pdate" data-order="'.$new_order.'" href="'.route('sort_purchased',['page'=>$current_page]).'">Dates</a></th>
                <th>Picture</th>
                <th><a class="col-sort" id="sort-name" data-order="'.$new_order.'" href="'.route('sort_purchased',['page'=>$current_page]).'">Articles</a></th>
                <th><a class="col-sort" id="sort-size" data-order="'.$new_order.'" href="'.route('sort_purchased',['page'=>$current_page]).'">sizes</a></th>
                <th><a class="col-sort" id="sort-color" data-order="'.$new_order.'" href="'.route('sort_purchased',['page'=>$current_page]).'">Color</a></th>
                <th><a class="col-sort" id="sort-quantity" data-order="'.$new_order.'" href="'.route('sort_purchased',['page'=>$current_page]).'">Quantity</a></th>
                <th><a class="col-sort" id="sort-price" data-order="'.$new_order.'" href="'.route('sort_purchased',['page'=>$current_page]).'">Prix yen</a></th>
                <th>Delete</th>
                <th><a class="col-sort" id="sort-total" data-order="'.$new_order.'" href="'.route('sort_purchased',['page'=>$current_page]).'">Total</a></th>
            </tr>';
        foreach($articles as $art){
            $output .='
            <tr>
                <td>'.$art->purchase_dates.'</td>
                <td><img class="img-cart" src="'.asset($art->small_images).'"></td>
                <td>'.$art->article_name.'</td>
                <td>'.$art->sizes.'</td>
                <td>'.$art->color.'</td>
                <td id="quantity">'.$art->quantity.'</td>
                <td id="price">'.number_format($art->price).'¥</td>
                <td>
                    <a class="del" href="'.route('delete_p_article', ['article'=>$art->id_article]).'">
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
                        $output .='<div class="page-surf"><a href="/purchased/'.$prev_page.'/'.$order.'/'.$request_col_name.'"><button><< </button></a></div>';
                    }
                    for($page = 1;$page<=$total_page;$page++){
                        if($current_page == $page){
                            $output .='<div class="page-white">'.$page.'</div>';
                        }else{
                            $output .='<div class="page"><a href="/purchased/'.$page.'/'.$order.'/'.$request_col_name.'"><button>'.$page.' </button></a></div>';
                        }
                    }
                    if($next_page<=$total_page){
                        $output .='<div class="page-surf"><a href="/purchased/'.$next_page.'/'.$order.'/'.$request_col_name.'"><button>>> </button></a></div>';
                    }
                }
            $output .='</div>';
        return $output;
    }
    /* solded articles OUTPUT */
    public static function order_solded_articles($id,$order,$col_name,$request_col_name,$depart,$articles_per_page,$current_page,$total_page){
        if($order!='desc' && $order!='asc'){
            $order = 'desc';
        }
        $sql="SELECT * FROM solded_articles_user WHERE id ='%s' ORDER BY %s %s LIMIT %s,%s";
        $articles = DB::select(sprintf($sql,$id,$col_name,$order,$depart,$articles_per_page));
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
                <th><a class="col-sort" id="sort-sdate" data-order="'.$new_order.'" href="'.route('sort_solded',['page'=>$current_page]).'">Dates</a></th>
                <th>Picture</th>
                <th><a class="col-sort" id="sort-name" data-order="'.$new_order.'" href="'.route('sort_solded',['page'=>$current_page]).'">Articles</a></th>
                <th><a class="col-sort" id="sort-size" data-order="'.$new_order.'" href="'.route('sort_solded',['page'=>$current_page]).'">sizes</a></th>
                <th><a class="col-sort" id="sort-color" data-order="'.$new_order.'" href="'.route('sort_solded',['page'=>$current_page]).'">Color</a></th>
                <th><a class="col-sort" id="sort-quantity" data-order="'.$new_order.'" href="'.route('sort_solded',['page'=>$current_page]).'">Quantity</a></th>
                <th><a class="col-sort" id="sort-price" data-order="'.$new_order.'" href="'.route('sort_solded',['page'=>$current_page]).'">Prix yen</a></th>
                <th>Delete</th>
                <th><a class="col-sort" id="sort-total" data-order="'.$new_order.'" href="'.route('sort_solded',['page'=>$current_page]).'">Total</a></th>
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
                    <a class="del" href="'.route('delete_s_article', ['article'=>$art->id_article]).'">
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
                        $output .='<div class="page-surf"><a href="/solded/'.$prev_page.'/'.$order.'/'.$request_col_name.'"><button><< </button></a></div>';
                    }
                    for($page = 1;$page<=$total_page;$page++){
                        if($current_page == $page){
                            $output .='<div class="page-white">'.$page.'</div>';
                        }else{
                            $output .='<div class="page"><a href="/solded/'.$page.'/'.$order.'/'.$request_col_name.'"><button>'.$page.' </button></a></div>';
                        }
                    }
                    if($next_page<=$total_page){
                        $output .='<div class="page-surf"><a href="/solded/'.$next_page.'/'.$order.'/'.$request_col_name.'"><button>>> </button></a></div>';
                    }
                }
            $output .='</div>';
        return $output;
    }
}