<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Str;
use App\Models\Shop_stock;
use App\Models\Shop;
use App\Models\User_admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class Shop_stock_controller extends Base_controller
{
    function stock_view(Request $request){
        $user = Auth::user();
        if($user!=null){
            $shop_ses = session()->get('shop_ses');
            $data['shop'] = Shop::get_shop($shop_ses['shop_name']);
            if($data['shop'] !== null){
                $shop_ses = array('id_shop'=>$data['shop']->id_shop,'shop_name'=>$data['shop']->shop_name);
                parent::stock_ses($request,$shop_ses);
                $articles_length = Shop_stock::get_count_articles($data['shop']->id_shop);
                $articles_per_page = 10;
                $min_page = 1;
                $data['admin'] = User_admin::get_admin($data['shop']->id_shop,$user->id);
                $orderpage = parent::page_and_orders($request->order,$request->page);
                $sort_colname = parent::sort_colname($request->col_name, 'creation_date');
                $next = $orderpage['currentpage'] +1;
                $prev = $orderpage['currentpage'] -1;
                $depart = ($orderpage['currentpage']-1)*$articles_per_page;
                $data['next_page'] = $next;
                $data['prev_page'] = $prev;
                $data['min_page'] = $min_page;
                $data['current_page'] = $orderpage['currentpage'];
                $data['total_page'] = ceil($articles_length->total / $articles_per_page);
                $data['order'] = $orderpage['order'];
                $col_name = parent::get_col_name($sort_colname);
                $data['col_name'] = $col_name;
                $data['admins'] = User_admin::get_admins_list($data['shop']->id_shop,$user->id);
                $data['categories'] = parent::getCategories_shop($data['shop']->id_shop);
                $data['articles'] = Shop_stock::get_articles($orderpage['order'],$col_name,$data['shop']->id_shop,$depart,$articles_per_page);
                return view('shop_stock',$data);
            }else{
                return redirect('/');
            }
        }else{
            return redirect('login')->with('login_error','ログインしてください。');
        }
    }
    function update_articles_view(Request $request){
        if(Auth::check()){
            $shop_ses = session()->get('shop_ses');
            $data['shop'] = Shop::get_shop($shop_ses['shop_name']);
            if($data['shop'] !== null){
                $article = Shop_stock::get_article($shop_ses['id_shop'],$request->id_article);
                if($article!=null){
                    $article->descriptions = parent::br_space_to_normal($article->descriptions);
                    $data['article'] = $article;
                    $user = Auth::user();
                    $data['admin'] = User_admin::get_admin($data['shop']->id_shop,$user->id);
                    $data['admins'] = User_admin::get_admins_list($data['shop']->id_shop,$user->id);
                    $data['categories_articles'] = parent::getCategories();
                    $data['categories'] = parent::getCategories_shop($data['shop']->id_shop);
                    return view('up_shop_article',$data);
                }else{
                    return redirect('/'.$data['shop']->shop_name.'/stock');
                }
            }else{
                return redirect('/');
            }
        }else{
            return redirect('login')->with('login_error','ログインしてください。');
        }
    }
    function update_articles_stock(Request $request){
        $user = Auth::user();
        $shop_ses = session()->get('shop_ses');
        if(Auth::check() && session()->has('shop_ses')){
            if(Gate::allows('isSubscribedShop')){
                $user = Auth::user();
                $request->validate([
                    'name' => 'required',
                    'price' => 'required | gte:100 | lte:1000001',
                    'size' => 'required',
                    'quantity' => 'required | gte:1',
                ]);
                $article = Shop_stock::get_article($shop_ses['id_shop'],$request->id_article);
                $description = parent::space_nl($request->description);
                $imgfile = $request->image;
                $filename="";
                if($imgfile == null){
                    $filename = Str::substr($article->large_images, 24);
                }else{
                    $filename = base64_encode($imgfile.''.time()).'.'.$imgfile->extension();
                }
                $b_path = parent::big_folders($shop_ses['shop_name']);
                $big_path = $b_path.''.$filename;
                $s_path = parent::small_folders($shop_ses['shop_name']);
                $small_path = $s_path.''.$filename;
                parent::image_upload($imgfile,$big_path,$small_path);
                $sql= "UPDATE articles SET id_categorie ='%s', article_name='%s', color='%s', sizes='%s', quantity='%s', price='%s', creation_date=NOW(), descriptions='%s', total_price = calctotal('%s','%s'), large_images='%s', small_images='%s', states='on sale' WHERE id_article='%s' AND id_shop='%s'";
                $sql = DB::update(sprintf($sql,$request->categorie,$request->name,$request->color,$request->size,$request->quantity,$request->price,$description,$request->price,$request->quantity,$big_path,$small_path,$request->id_article,$shop_ses['id_shop']));
                $success_mess = 'この商品はアップデートされました。';
                return redirect('/'.$shop_ses['shop_name'].'/up_article/'.$request->id_article.'/'.$request->name)->with('success',$success_mess);
            }else{
                return redirect('/'.$shop_ses['shop_name'].'/up_article/'.$request->id_article.'/'.$request->name)->with('error','プランに登録してください！！');
            }
        }else{
            return redirect('/');
        }
    }
    public function update_article(Request $request){
        $shop = session()->get('shop_ses');
        if($request->action == "minus"){
            $article = Shop_stock::get_article($shop['id_shop'],$request->article);
            $validation = Validator::make($request->all(), [
                'quantity' => 'required | | lt:'.$article->quantity
            ]);
            if($validation->fails()){
                $data = array(
                    'quantity_error' => $validation->errors()->first('quantity')
                );
                echo json_encode($data);
            }else{
                sleep(2);
                $new_quantity = $article->quantity - $request->quantity;
                Shop_stock::update_quantity($shop['id_shop'],$request->article,$new_quantity,$article->price);
                $data = array(
                    'quantity' => $new_quantity,
                    'price' => number_format($article->price),
                    'total' => number_format($article->price * $new_quantity)
                );
                echo json_encode($data);
            }
        }else if($request->action == "plus"){
            $article = Shop_stock::get_article($shop['id_shop'],$request->article);
            $validation = Validator::make($request->all(), [
                'quantity' => 'required | gt:0'
            ]);
            if($validation->fails()){
                $data = array(
                    'quantity_error' => $validation->errors()->first('quantity')
                );
                echo json_encode($data);
            }else{
                sleep(1);
                $new_quantity = $article->quantity + $request->quantity;
                Shop_stock::update_quantity($shop['id_shop'],$request->article,$new_quantity,$article->price);
                $data = array(
                    'quantity' => $new_quantity,
                    'price' => number_format($article->price),
                    'total' => number_format($article->price * $new_quantity)
                );
                echo json_encode($data);
            }
        }
    }
    /* delete article */
    function delete_article(Request $request){
        $shop = session()->get('shop_ses');
        $article = Shop_stock::get_article($shop['id_shop'],$request->article);;
        Shop_stock::delete_article($shop['id_shop'],$article->id_article);
    }
    /* sort article stock */
    function sort_table(Request $request){
        $col_name = parent::get_col_name($request->col_name);
        $user = Auth::user();
        $shop = session()->get('shop_ses');
        $articles_length = Shop_stock::get_count_articles($shop['id_shop']);
        $articles_per_page = 10;
        $page =intval($request->page);
        $total_page = ceil($articles_length->total / $articles_per_page);
        $depart = ($page-1)*$articles_per_page;
        $data['articles'] = Shop_stock::order_articles($shop['id_shop'],$shop['shop_name'],$request->order,$col_name,$request->col_name,$depart,$articles_per_page,$page,$total_page);
        echo json_encode($data);
    }
    function soded_view(Request $request){
        $user = Auth::user();
        if($user!=null){
            $shop_ses = session()->get('shop_ses');
            $data['shop'] = Shop::get_shop($shop_ses['shop_name']);
            if($data['shop'] !== null){
                $shop_ses = array('id_shop'=>$data['shop']->id_shop,'shop_name'=>$data['shop']->shop_name);
                parent::stock_ses($request,$shop_ses);
                $articles_length = Shop_stock::count_s_articles($data['shop']->id_shop);
                $articles_per_page = 10;
                $min_page = 1;
                $orderpage = parent::page_and_orders($request->order,$request->page);
                $sort_colname = parent::sort_colname($request->col_name, 'sort-sdate');
                $next = $orderpage['currentpage'] +1;
                $prev = $orderpage['currentpage'] -1;
                $depart = ($orderpage['currentpage']-1)*$articles_per_page;
                $data['next_page'] = $next;
                $data['prev_page'] = $prev;
                $data['min_page'] = $min_page;
                $data['current_page'] = $orderpage['currentpage'];
                $data['total_page'] = ceil($articles_length->total / $articles_per_page);
                $data['order'] = $orderpage['order'];
                $col_name = parent::get_col_name($sort_colname);
                $data['col_name'] = $sort_colname;
                $data['admin'] = User_admin::get_admin($data['shop']->id_shop,$user->id);
                $data['admins'] = User_admin::get_admins_list($data['shop']->id_shop,$user->id);
                $data['categories'] = parent::getCategories_shop($data['shop']->id_shop);
                $data['articles'] = Shop_stock::get_s_articles($orderpage['order'],$col_name,$data['shop']->id_shop,$depart,$articles_per_page);
                return view('shop_solded',$data);
            }else{
                return redirect('/');
            }
        }else{
            return redirect('login')->with('login_error','ログインしてください。');
        }
    }
    function sort_solded_articles(Request $request){
        $sort_colname = $this->sort_colname($request->col_name, 'sort-sdate');
        $col_name = parent::get_col_name($sort_colname);
        $user = Auth::user();
        $shop = session()->get('shop_ses');
        $articles_length = Shop_stock::count_s_articles($shop['id_shop']);
        $articles_per_page = 10;
        $page =intval($request->page);
        $total_page = ceil($articles_length->total / $articles_per_page);
        $depart = ($page-1)*$articles_per_page;
        $data['articles'] = Shop_stock::order_s_articles($shop['id_shop'],$shop['shop_name'],$request->order,$col_name,$request->col_name,$depart,$articles_per_page,$page,$total_page);
        echo json_encode($data);
    }
    function delete_solded_article(Request $request){
        $shop = session()->get('shop_ses');
        $article = Shop_stock::get_article($shop['id_shop'],$request->article);;
        Shop_stock::delete_s_article($shop['id_shop'],$article->id_article);
    }
}
