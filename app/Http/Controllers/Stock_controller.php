<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Article_controller;
use App\Models\Stock;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Validator;
class Stock_controller extends Base_controller
{
    function stock_view(Request $request){
        $user = Auth::user();
        $articles_length = Stock::get_count_articles($user->id);
        $articles_per_page = 10;
        $min_page = 1;
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
        $data['col_name'] = $request->col_name;
        $col_name = parent::get_col_name($sort_colname);
        $data['articles'] = Stock::get_articles($orderpage['order'],$col_name,$user->id,$depart,$articles_per_page);
        return view('stock',$data);
    }
    
    public function update_articles_view(Request $request){
        $article = Article_controller::get_article_name($request->id_article,$request->article_name);
        if($article==null){
            return redirect('/stock');
        }else{
            $article->descriptions = parent::br_space_to_normal($article->descriptions);;
            $data['article'] = $article;
            return view('update_article',$data);
        }
    }
    function update_articles_stock(Request $request){
        $article = Article_controller::get_article($request->id_article);
        $shop_name = null;
        if(Auth::check()){
                $user = Auth::user();
                $request->validate([
                    'name' => 'required',
                    'price' => 'required | lte:100000001',
                    'price' => 'gte:10',
                    'size' => 'required',
                    'quantity' => 'required | gte:1',
                ]);
                $name = htmlspecialchars($request->name);
                $size = htmlspecialchars($request->size);
                $description = htmlspecialchars($request->description);
                $description = parent::space_nl($description);

                $imgfile = $request->image;
                $filename="";
                if($imgfile == null){
                    $filename = Str::substr($article->large_images, 11);
                }else{
                    $filename = base64_encode($imgfile.''.time()).'.'.$imgfile->extension();
                }
                $b_path = parent::big_folders($shop_name);
                $big_path = $b_path.''.$filename;
                $s_path = parent::small_folders($shop_name);
                $small_path = $s_path.''.$filename;
                parent::image_upload($imgfile,$big_path,$small_path);
                $total = parent::calctotal($request->quantity,$request->price);
                $sql= "UPDATE articles SET id_categorie ='%s', article_name='%s', color='%s', sizes='%s', quantity='%s', price='%s', creation_date=NOW(), descriptions='%s', total_price = '%s', large_images='%s', small_images='%s', states='on sale' WHERE id_article='%s'";
                $sql = DB::update(sprintf($sql,$request->categorie,$name,$request->color,$size,$request->quantity,$request->price,$description,$total,$big_path,$small_path,$request->id_article));
                $success_mess = 'Modification réussi!';
                return redirect('up_article/'.$article->id_article.'/'.$request->name)->with('success',$success_mess);
        }else{
            return redirect('/');
        }
    }
    public function update_article(Request $request){
        if($request->action == "minus"){
            $article = Article_controller::get_article($request->article);
            $validation = Validator::make($request->all(), [
                'quantity' => 'required | | lt:'.$article->quantity
            ]);
            if($validation->fails()){
                $data = array(
                    'quantity_error' => $validation->errors()->first('quantity')
                );
                echo json_encode($data);
            }else{
                $new_quantity = $article->quantity - $request->quantity;
                $total = parent::calctotal($new_quantity,$article->price);
                $sql= "UPDATE articles SET quantity='%s',total_price='%s'  WHERE id_article='%s'";
                $sql = DB::update(sprintf($sql,$new_quantity,$total,$request->article));
                $data = array(
                    'quantity' => $new_quantity,
                    'price' => number_format($article->price),
                    'total' => number_format($article->price * $new_quantity)
                );
                echo json_encode($data);
            }
        }else if($request->action == "plus"){
            $article = Article_controller::get_article($request->article);
            $validation = Validator::make($request->all(), [
                'quantity' => 'required | gt:0'
            ]);
            if($validation->fails()){
                $data = array(
                    'quantity_error' => $validation->errors()->first('quantity')
                );
                echo json_encode($data);
            }else{
                $new_quantity = $article->quantity + $request->quantity;
                $total = parent::calctotal($new_quantity,$article->price);
                $sql= "UPDATE articles SET quantity='%s',total_price='%s'  WHERE id_article='%s'";
                $sql = DB::update(sprintf($sql,$new_quantity,$total,$request->article));
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
        $article = Article_controller::get_article_delete($request->article,$request->name);
        $sql = "UPDATE articles SET states='deleted',delete_date=now()  WHERE id_article='%s'";
        $sql = DB::update(sprintf($sql,$article->id_article));
    }

    /* sort article user */
    public function sort_table(Request $request){
        $col_name = parent::get_col_name($request->col_name);
        $user = Auth::user();
        $articles_length = Stock::get_count_articles($user->id);
        $articles_per_page = 10;
        $page =intval($request->page);
        $total_page = ceil($articles_length->total / $articles_per_page);
        $depart = ($page-1)*$articles_per_page;
        $data['articles'] = Stock::order_articles($user->id,$request->order,$col_name,$request->col_name,$depart,$articles_per_page,$page,$total_page);
        echo json_encode($data);
    }

    /* purchased view */
    function purchased_view(Request $request){
        $user = Auth::user();
        $articles_length = Stock::count_p_articles($user->id);
        $articles_per_page = 10;
        $min_page = 1;
        $orderpage = parent::page_and_orders($request->order,$request->page);
        $sort_colname = parent::sort_colname($request->col_name, 'sort-pdate');
        $next = $orderpage['currentpage'] +1;
		$prev = $orderpage['currentpage'] -1;
        $depart = ($orderpage['currentpage']-1)*$articles_per_page;
        $data['next_page'] = $next;
		$data['prev_page'] = $prev;
		$data['min_page'] = $min_page;
		$data['current_page'] = $orderpage['currentpage'];
		$data['total_page'] = ceil($articles_length->total / $articles_per_page);
        $data['order'] = $orderpage['order'];
        $data['col_name'] = $request->col_name;
        $col_name = parent::get_col_name($sort_colname);
        $data['articles'] = Stock::get_p_articles($orderpage['order'],$col_name,$user->id,$depart,$articles_per_page);
        return view('purchased',$data);
    }
    
    function sort_purchased_articles(Request $request){
        $sort_colname = $this->sort_colname($request->col_name, 'sort-pdate');
        $col_name = parent::get_col_name($sort_colname);
        $user = Auth::user();
        $articles_length = Stock::count_p_articles($user->id);
        $articles_per_page = 10;
        $page =intval($request->page);
        $total_page = ceil($articles_length->total / $articles_per_page);
        $depart = ($page-1)*$articles_per_page;
        $data['articles'] = Stock::order_purchased_articles($user->id,$request->order,$col_name,$request->col_name,$depart,$articles_per_page,$page,$total_page);
        echo json_encode($data);
    }

    /* delete purchased article */
    function delete_p_article(Request $request){
        $article = Stock::get_purchased_delete($request->article);
        $sql = "UPDATE purchase SET states='deleted',delete_date=now()  WHERE id_article='%s'";
        $sql = DB::update(sprintf($sql,$article->id_article));
    }

    /* solded view */
    function solded_view(Request $request){
        $user = Auth::user();
        $articles_length = Stock::count_s_articles($user->id);
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
        $data['col_name'] = $request->col_name;
        $col_name = parent::get_col_name($sort_colname);
        $data['articles'] = Stock::get_s_articles($orderpage['order'],$col_name,$user->id,$depart,$articles_per_page);
        return view('solded',$data);
    }

    function sort_solded_articles(Request $request){
        $sort_colname = $this->sort_colname($request->col_name, 'sort-sdate');
        $col_name = parent::get_col_name($sort_colname);
        $user = Auth::user();
        $articles_length = Stock::count_p_articles($user->id);
        $articles_per_page = 10;
        $page =intval($request->page);
        $total_page = ceil($articles_length->total / $articles_per_page);
        $depart = ($page-1)*$articles_per_page;
        $data['articles'] = Stock::order_solded_articles($user->id,$request->order,$col_name,$request->col_name,$depart,$articles_per_page,$page,$total_page);
        echo json_encode($data);
    }
    
    /* delete purchased article */
    function delete_s_article(Request $request){
        $article = Stock::get_solded_delete($request->article);
        $sql = "UPDATE user_solded_articles SET states='deleted',delete_date=now()  WHERE id_article='%s'";
        $sql = DB::update(sprintf($sql,$article->id_article));
    }
}
