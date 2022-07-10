<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Base_controller;
use App\Http\Controllers\Article_controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
class Cart_controller extends Base_controller
{
    function store(Request $request){
        $duplicata= Cart::search(function ($cartItem, $rowId) use ($request) {
            return $cartItem->id == $request->article;
        });
        if($duplicata->isNotEmpty()){
            $data['error'] = '<span style="color:red">!!この商品はもうカートにあります!!</span>';
            echo json_encode($data);
        }else{
            $article = Article_controller::get_article($request->article);
            Cart::add($article->id_article,$article->article_name,1,$article->total_price);
            $data['success'] = '<div class="cart-number"><span>'.Cart::count().'</span></div>';
            echo json_encode($data);
        }
    }
    function cart_from_article(Request $request){
        $article = Article_controller::get_article($request->article);
        $request->validate([
            'quantity' => 'required | gte:1 | lte:'.$article->quantity,
        ]);
        $duplicata= Cart::search(function ($cartItem, $rowId) use ($request) {
            return $cartItem->id == $request->article;
        });
        if($duplicata->isNotEmpty()){
            return redirect('article/'.$article->id_article.'/'.$article->article_name)->with('success','This Article is already in your cart');
        }else{
            Cart::add($article->id_article,$article->article_name,$request->quantity,$article->total_price);
            $data['success'] = '<div class="cart-number"><span>'.Cart::count().'</span></div>';
            return redirect('article/'.$article->id_article.'/'.$article->article_name);
        }
    }
    function view_cart(){
        $ids = Cart::content()->pluck('id')->toArray();
        if(empty($ids)){
            $articles = array();
        }else{
            $sql="SELECT * FROM articles WHERE id_article IN (".implode(",",$ids).")";
            $articles = DB::select($sql);
        }
        $data['articles'] = $articles;
        return view('shopping_cart',$data);
    }
    function getRowid($id_article){
        $cart_article = Cart::search(function ($cartItem, $rowId) use ($id_article) {
            return $cartItem->id == $id_article;
        });
        $rowid = $cart_article->pluck('rowId');
        return $rowid[0];
    }
    function update_quantity(Request $request){
        $article = Article_controller::get_article($request->article);
        $request->validate([
            'new_quantity' => 'required | gte:1 | lte:'.$article->quantity,
        ]);
        $rowid = $this->getRowid($request->article);
        Cart::update($rowid, $request->new_quantity);
        return redirect('shoppingcart');
    }
    function cart_delete(Request $request){
        $rowid = $this->getRowid($request->article);
        Cart::remove($rowid);
        if(Cart::count()==0){
            Cart::destroy();
        }
        return redirect('shoppingcart');
    }
    /* with Paypal stand by */
    function user_solded_articles($id,$id_article,$price,$quantity){
        $sql="INSERT INTO user_solded_articles(id,id_article,solded_dates,quantity,total_price,states) VALUES('%s','%s',NOW(),'%s',calctotal('%s','%s'),'solded')";
        DB::insert(sprintf($sql,$id,$id_article,$quantity,$price,$quantity));
    }
    function insert_purchase($id,$id_article,$price,$quantity){
        $sql="INSERT INTO purchase(id,id_article,purchase_dates,quantity,total_price,states) VALUES('%s','%s',NOW(),'%s',calctotal('%s','%s'),'purchased')";
        DB::insert(sprintf($sql,$id,$id_article,$quantity,$price,$quantity));
    }
    function insert_solded_shop($id_shop,$id,$id_article,$price,$quantity){
        $sql="INSERT INTO solded_articles(id_shop,id,id_article,solded_dates,quantity,total_price,states) VALUES('%s','%s','%s',NOW(),'%s',calctotal('%s','%s'),'solded')";
        DB::insert(sprintf($sql,$id_shop,$id,$id_article,$quantity,$price,$quantity));
    }
    function buy_articles(Request $request){
        $user = Auth::user();
        $rowid = $this->getRowid($request->article);
        $article = Article_controller::get_article($request->article);
        if($user == null){
            return redirect('shoppingcart')->with('message','Please Login first !');
        }else if($article == null){
            return redirect('shoppingcart');
        }else{
            $article_cart = Cart::get($rowid);
            if($article->id_shop==0){
                $this->insert_purchase($user->id,$article->id_article,$article->price,$article_cart->qty);
                $this->user_solded_articles($article->id,$article->id_article,$article->price,$article_cart->qty);
                return redirect('shoppingcart')->with('message','Thank you for your purchase!');
            }else{
                $this->insert_purchase($user->id,$article->id_article,$article->price,$article_cart->qty);
                $this->insert_solded_shop($article->id_shop,$article->id,$article->id_article,$article->price,$article_cart->qty);
                return redirect('shoppingcart')->with('message','Thank you for your purchase!');
            }
        }
    }
    function delete_cart(){
        Cart::destroy();
        return redirect('shoppingcart');
    }
}
