<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Shop extends Model
{
    use HasFactory;
    public static function create_shop($id,$shop_name,$phone_number,$shop_email){
        $sql ="INSERT INTO shops(id,shop_name,phone_number,shop_email,creation_date) VALUES('%s','%s','%s','%s',now())";
        $sql = DB::insert(sprintf($sql,$id,$shop_name,$phone_number,$shop_email));
    }
    public static function get_shop($shop_name){
        $sql="SELECT * FROM user_admin_shop WHERE shop_name='%s'";
		$sql = sprintf($sql,$shop_name);
		$shop = collect(\DB::select($sql))->first();
        return $shop;
    }
    public static function add_admin_shop($id,$id_shop){
        $sql ="INSERT INTO user_shop(id,id_shop) VALUES('%s','%s')";
        $sql = DB::insert(sprintf($sql,$id,$id_shop));
    }
    
    public static function get_count_articles($id_shop){
        $sql="SELECT COUNT(*) AS total FROM articles WHERE states!='deleted' AND id_shop = '%s'";
        $count = collect(\DB::select(sprintf($sql,$id_shop)))->first();
        return $count->total;
    }
    public static function get_articles($id_shop,$depart,$articles_per_page){
        $sql="SELECT * FROM articles WHERE states!='deleted' AND quantity!=0  AND id_shop='%s' ORDER BY creation_date DESC LIMIT %s,%s";
        $articles = DB::select(sprintf($sql,$id_shop,$depart,$articles_per_page));
        return $articles;
    }
    public static function get_shop_article($id_article,$article_name,$id_shop){
        $sql="SELECT * FROM articles_categories WHERE states!='deleted' AND id_article='%s' AND article_name='%s' AND id_shop='%s'";
		$sql = sprintf($sql,$id_article,$article_name,$id_shop);
		$article = collect(\DB::select($sql))->first();
        return $article;
    }
    public static function count_search($id_shop,$search){
        $total= collect(\DB::table('articles')
                        ->select(DB::raw('COUNT(*) as total'))
                        ->where('id_shop',$id_shop)
                        ->where('states','!=','deleted')
                        ->where('article_name','LIKE','%'.$search.'%')
                        ->get())->first();
        return $total->total;
    }
    public static function search_articles($id_shop,$search,$depart,$articles_per_page){
        $articles = DB::table('articles')
                        ->where('id_shop',$id_shop)
                        ->where('states','!=','deleted')
                        ->where('article_name','LIKE','%'.$search.'%')
                        ->orderBy('creation_date', 'desc')
                        ->offset($depart)
                        ->limit($articles_per_page)
                        ->get();
        return $articles;
    }
    public static function count_categorie_articles($id_shop,$categorie){
        $total= collect(\DB::table('articles_categories')
                        ->select(DB::raw('COUNT(*) as total'))
                        ->where('id_shop','=',$id_shop)
                        ->where('states','!=','deleted')
                        ->where('categorie','=',$categorie)
                        ->get())->first();
        return $total->total;
    }
    public static function search_categorie_articles($id_shop,$categorie,$depart,$articles_per_page){
        $articles = DB::table('articles_categories')
                        ->where('id_shop','=',$id_shop)
                        ->where('states','!=','deleted')
                        ->where('categorie','=',$categorie)
                        ->orderBy('creation_date', 'desc')
                        ->offset($depart)
                        ->limit($articles_per_page)
                        ->get();
        return $articles;
    }
    public static function get_commercial(){
        $cover= collect(\DB::table('commercials')
                        ->orderBy('dates', 'desc')
                        ->get())->first();
        return $cover;
    }
}
