<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Base_controller;
use App\Http\Controllers\Article_controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\PurchaseMail;
use App\Mail\SoldedMail;
class Cart_controller extends Base_controller
{
    /* store article in cart */
    function store(Request $request){
        if (Auth::check()) {
            $article_cart = DB::table('cart')->where('id_article',$request->article)->get()->first();
            if($article_cart!=null){
                $data['error'] = '<span style="color:red">!!Cet article est déjà dans votre panier!!</span>';
                echo json_encode($data);
            }else{
                $article = Article_controller::get_article($request->article);
                DB::table('cart')->insert([
                    'id_user' => Auth::id(),
                    'id_article' => $article->id_article,
                    'quantity' => 1
                ]);
                $cart_quantity = parent::getCartarticlequantity();
                $data['success'] = '<div class="cart-number"><span>'.$cart_quantity.'</span></div>';
                echo json_encode($data);
            }
        }else{
            $duplicata= Cart::search(function ($cartItem, $rowId) use ($request) {
                return $cartItem->id == $request->article;
            });
            if($duplicata->isNotEmpty()){
                $data['error'] = '<span style="color:red">!!Cet article est déjà dans votre panier!!</span>';
                echo json_encode($data);
            }else{
                $article = Article_controller::get_article($request->article);
                Cart::add($article->id_article,$article->article_name,1,$article->total_price);
                $data['success'] = '<div class="cart-number"><span>'.Cart::count().'</span></div>';
                echo json_encode($data);
            }
        }
    }
    /* add article from the article description page */
    function cart_from_article(Request $request){
        if (Auth::check()) {
            $article = Article_controller::get_article($request->article);
            $request->validate([
                'quantity' => 'required | lte:'.$article->quantity,
                'quantity' => 'gte:1',
            ]);
            if($article!=null){
                return redirect('article/'.$article->id_article.'/'.$article->article_name)->with('success','!!Cet article est déjà dans votre panier!!');
            }else{
                DB::table('cart')->insert([
                    'id_user' => Auth::id(),
                    'id_article' => $article->id_article,
                    'quantity' => $request->quantity
                ]);
                return redirect('article/'.$article->id_article.'/'.$article->article_name);
            }
        }else{
            $article = Article_controller::get_article($request->article);
            $request->validate([
                'quantity' => 'required | lte:'.$article->quantity,
                'quantity' => 'gte:1',
            ]);
            $duplicata= Cart::search(function ($cartItem, $rowId) use ($request) {
                return $cartItem->id == $request->article;
            });
            if($duplicata->isNotEmpty()){
                return redirect('article/'.$article->id_article.'/'.$article->article_name)->with('success','!!Cet article est déjà dans votre panier!!');
            }else{
                Cart::add($article->id_article,$article->article_name,$request->quantity,$article->total_price);
                $data['success'] = '<div class="cart-number"><span>'.Cart::count().'</span></div>';
                return redirect('article/'.$article->id_article.'/'.$article->article_name);
            }
        }
    }

    function view_cart(){
        if (Auth::check()) {
            $data['articles'] = DB::table('cart_article')->where('id_user',Auth::id())->get();
            return view('shopping_cart',$data);
        }else{
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
    }

    function getRowid($id_article){
        $cart_article = Cart::search(function ($cartItem, $rowId) use ($id_article) {
            return $cartItem->id == $id_article;
        });
        $rowid = $cart_article->pluck('rowId');
        return $rowid[0];
    }

    function update_quantity(Request $request){
        if (Auth::check()) {
            $article = Article_controller::get_article($request->article);
            $request->validate([
                'new_quantity' => 'required',
                'new_quantity' => 'gte:1'
            ]);
            if($request->new_quantity>=$article->quantity){
                return redirect('shoppingcart');
            }
            DB::table('cart')
                ->where('id_article', $request->article)
                ->update(['quantity' => $request->new_quantity]);
        }else{
            $article = Article_controller::get_article($request->article);
            $request->validate([
                'new_quantity' => 'required',
                'new_quantity' => 'gte:1'
            ]);
            if($request->new_quantity>=$article->quantity){
                return redirect('shoppingcart');
            }
            $rowid = $this->getRowid($request->article);
            Cart::update($rowid, $request->new_quantity);
        }
        return redirect('shoppingcart');
    }

    function cart_delete(Request $request){
        if (Auth::check()) {
            DB::table('cart')->where('id_user', Auth::id())->where('id_article',$request->article)->delete();
        }else{
            $rowid = $this->getRowid($request->article);
            Cart::remove($rowid);
            if(Cart::count()==0){
                Cart::destroy();
            }
        }
        return redirect('shoppingcart');
    }

    /* with Paypal stand by */
    function user_solded_articles($id,$id_article,$price,$quantity){
        $total = parent::calctotal($quantity,$price);
        $sql="INSERT INTO user_solded_articles(id,id_article,solded_dates,quantity,total_price,states) VALUES('%s','%s',NOW(),'%s','%s','solded')";
        DB::insert(sprintf($sql,$id,$id_article,$quantity,$total));
    }

    function insert_purchase($id,$id_article,$price,$quantity){
        $total = parent::calctotal($quantity,$price);
        $sql="INSERT INTO purchase(id,id_article,purchase_dates,quantity,total_price,states) VALUES('%s','%s',NOW(),'%s','%s','purchased')";
        DB::insert(sprintf($sql,$id,$id_article,$quantity,$total));
    }

    function insert_solded_shop($id_shop,$id,$id_article,$price,$quantity){
        $total = parent::calctotal($quantity,$price);
        $sql="INSERT INTO solded_articles(id_shop,id,id_article,solded_dates,quantity,total_price,states) VALUES('%s','%s','%s',NOW(),'%s','%s','solded')";
        DB::insert(sprintf($sql,$id_shop,$id,$id_article,$quantity,$total));
    }
    
    function buy_articles(Request $request, DB $db){
        $user = Auth::user();
        $article = Article_controller::get_article($request->article);
        if($user == null){
            return redirect('shoppingcart')->with('message','Veuillez vous connecter pour faire un achat.');
        }else if($article == null){
            return redirect('shoppingcart');
        }else{
            $db::beginTransaction();
            try {
                $article_cart = $db::table('cart_article')->where('id_user',$user->id)->where('id_article',$request->article)->get()->first();
                $this->insert_purchase($user->id,$article->id_article,$article->price,$article_cart->qty);
                $this->user_solded_articles($article->id,$article->id_article,$article->price,$article_cart->qty);
                $db::table('cart')->where('id_user', $user->id)->where('id_article',$article->id_article)->delete();
                $seller_user = $db::table('users')->where('id',$article->id)->get()->first();
                $db::commit();
                return redirect('shoppingcart')->with('message','Merci de votre achat!');
            } catch (\Throwable $th) {
                $db::rollback();
                return redirect('shoppingcart')->with('message','Un erreur s\'est produit.');
            }
        }
    }

    function delete_cart(){
        if (Auth::check()) {
            DB::table('cart')->where('id_user', Auth::id())->delete();
        }else{
            Cart::destroy();
        }
        return redirect()->back();
    }
}
