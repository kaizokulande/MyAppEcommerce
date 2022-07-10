<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Base_controller;
use App\Http\Controllers\Users_controller;
use App\Http\Controllers\Article_controller;
use App\Http\Controllers\Cart_controller;
use App\Http\Controllers\Stock_controller;
use App\Http\Controllers\Shop_controller;
use App\Http\Controllers\Shop_stock_controller;
use App\Http\Controllers\Setting_controller;
use App\Http\Controllers\SubscriptionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/',[Article_controller::class,'home']);
Route::get('/page/{page}',[Article_controller::class,'home']);
Route::get('/article/{id_article}/{article_name}',[Article_controller::class,'view_article']);
/* search */
Route::get('/articles',[Article_controller::class,'view_search']);
Route::get('/articles/{search}/page/{page}',[Article_controller::class,'view_search']);
/* /search */
/* click categories */
Route::get('/cat/{categorie}',[Article_controller::class,'view_categories']);
Route::get('/cat/{categorie}/page/{page}',[Article_controller::class,'view_categories']);
/* /categories */

Route::get('/register',[Base_controller::class,'view_register']);
Route::get('/login',[Base_controller::class,'view_login']);
Route::get('/createshop',[Base_controller::class,'view_createshop'])->name('createshop');
Route::get('/myshop',[Base_controller::class,'view_shop']);
Route::get('/sellarticle',[Base_controller::class,'view_sellarticle']);
/* Subscriptions */

Route::get('/plans',[SubscriptionController::class,'view_subscription'])->name('plans');
Route::get('/plans_subscribed',[SubscriptionController::class,'view_subscribed'])->name('plans_subscribed');
Route::get('/plans/subscribe/{subs_id}',[SubscriptionController::class,'subscribe']);
Route::get('/plans_up/subscribe/{subs_id}',[SubscriptionController::class,'subscribe_update'])->name('plans_update');

Route::get('/add_article',[Base_controller::class,'view_cart']);
/* Route::post('/article_upload',[Article_controller::class,'upload_article'])->name('uploadimage'); */
Route::post('/article_upload',[Article_controller::class,'upload_article']);

/* register and login */
Route::post('user_register',[Users_controller::class,'user_register']);
Route::get('/register/email_confirmation/{code}',[Users_controller::class,'confirm_email']);
Route::post('login',[Users_controller::class,'user_login']);
/* cart */
Route::post('/add_to_cart',[Cart_controller::class,'store'])->name('add_to_cart');
Route::get('/shoppingcart',[Cart_controller::class,'view_cart']);
Route::post('/qty_up',[Cart_controller::class,'update_quantity']);
Route::get('/delete_cart',[Cart_controller::class,'cart_delete']);
Route::post('/add_cart',[Cart_controller::class,'cart_from_article']);
Route::get('/buy',[Cart_controller::class,'buy_articles']);
Route::get('/delete_all_cart',[Cart_controller::class,'delete_cart']);
/* Stock */
Route::get('/stock',[Stock_controller::class,'stock_view'])->name('stock');
Route::get('/stock/{page}/{order}',[Stock_controller::class,'stock_view'])->name('stock');
Route::get('/stock/{page}/{order}/{col_name}',[Stock_controller::class,'stock_view'])->name('stock');
Route::get('/update',[Stock_controller::class,'update_article'])->name('update_article');
Route::get('/delete',[Stock_controller::class,'delete_article'])->name('delete_article');
Route::get('/sort_articles',[Stock_controller::class,'sort_table'])->name('sort');
/* purchased */
Route::get('/purchased',[Stock_controller::class,'purchased_view'])->name('purchased');
Route::get('/purchased/{page}/{order}',[Stock_controller::class,'purchased_view'])->name('purchased');
Route::get('/purchased/{page}/{order}/{col_name}',[Stock_controller::class,'purchased_view'])->name('purchased');
Route::get('/deletepurchased',[Stock_controller::class,'delete_p_article'])->name('delete_p_article');
Route::get('/sort_purchased',[Stock_controller::class,'sort_purchased_articles'])->name('sort_purchased');
/* solded */
Route::get('/solded',[Stock_controller::class,'solded_view'])->name('solded');
Route::get('/solded/{page}/{order}',[Stock_controller::class,'solded_view'])->name('solded');
Route::get('/solded/{page}/{order}/{col_name}',[Stock_controller::class,'solded_view'])->name('solded');
Route::get('/deletesolded',[Stock_controller::class,'delete_s_article'])->name('delete_s_article');
Route::get('/sort_solded',[Stock_controller::class,'sort_solded_articles'])->name('sort_solded');

/* shop */
Route::post('/shopsakusei',[Shop_controller::class,'create_chop']);
Route::get('/{shop_name}',[Shop_controller::class,'shop_view']);
Route::get('/{shop_name}/page/{page}',[Shop_controller::class,'shop_view']);
Route::get('/{shop_name}/addarticle',[Shop_controller::class,'add_article_view']);
Route::post('/{shop_name}/upload_article',[Shop_controller::class,'upload_article']);
Route::get('/{shopname}/article/{id_article}/{article_name}',[Shop_controller::class,'view_shop_article']);
/* shop_search */
Route::get('/shop/{shop_name}/articles',[Shop_controller::class,'search_view']);
Route::get('/shop/{shop_name}/articles/{search}/page/{page}',[Shop_controller::class,'search_view']);
/* /shop_search */
/* shop categories */
Route::get('/shop/{shop_name}/categorie/{categorie}',[Shop_controller::class,'categorie_view']);
Route::get('/shop/{shop_name}/categorie/{categorie}/page/{page}',[Shop_controller::class,'categorie_view']);
/* /shop categories */
/* shop stock */
Route::get('/{shop_name}/stock',[Shop_stock_controller::class,'stock_view'])->name('shop_stock');
Route::get('/{shop_name}/stock/{page}/{order}',[Shop_stock_controller::class,'stock_view'])->name('shop_stock');
Route::get('/{shop_name}/stock/{page}/{order}/{col_name}',[Shop_stock_controller::class,'stock_view'])->name('shop_stock');
Route::get('/shop/shop_update',[Shop_stock_controller::class,'update_article'])->name('update_shop_article');
Route::get('/shop/shop_delete',[Shop_stock_controller::class,'delete_article'])->name('delete_shop_article');
Route::get('/shop/sort_articles_shop',[Shop_stock_controller::class,'sort_table'])->name('sort-shop');
/* shop solded */
Route::get('/{shop_name}/shop_solded',[Shop_stock_controller::class,'soded_view'])->name('solded');
Route::get('/{shop_name}/shop_solded/{page}/{order}',[Shop_stock_controller::class,'soded_view'])->name('solded');
Route::get('/{shop_name}/shop_solded/{page}/{order}/{col_name}',[Shop_stock_controller::class,'soded_view'])->name('solded');
Route::get('/shop/sort_s_solded',[Shop_stock_controller::class,'sort_solded_articles'])->name('sort-shop-solded');
Route::get('/shop/shop_s_delete',[Shop_stock_controller::class,'delete_solded_article'])->name('delete_solded_article');

/* shop setting */
Route::get('/{shop_name}/setting',[Setting_controller::class,'setting_view'])->name('setting');
Route::post('/shop/change_name',[Setting_controller::class,'change_name'])->name('setting_name');
Route::post('/shop/change_email',[Setting_controller::class,'change_email'])->name('setting_email');
Route::post('/shop/change_phone',[Setting_controller::class,'change_phone'])->name('setting_phone');
Route::post('/shop/change_link',[Setting_controller::class,'change_web'])->name('setting_web');
Route::post('/shop/users_admin',[Setting_controller::class,'search_user'])->name('user_search');
Route::post('/shop/add_admin',[Setting_controller::class,'add_admin'])->name('admin_add');
Route::get('/shop/del_adm/{user}',[Setting_controller::class,'adm_del']);
Route::post('/shop/add_cm',[Setting_controller::class,'add_commercial']);

/* log out */
Route::get('/u/logout',[Users_controller::class,'user_logout']);
Route::get('/u/users',[Users_controller::class,'users']);
require __DIR__.'/auth.php';
