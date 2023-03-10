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
use App\Http\Controllers\ForgotPasswordController;

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

Route::get('/add_article',[Base_controller::class,'view_cart']);
Route::post('/article_upload',[Article_controller::class,'upload_article']);

/* register and login */
Route::post('user_register',[Users_controller::class,'user_register']);
Route::get('/register/email_confirmation/{code}',[Users_controller::class,'confirm_email']);
Route::post('login',[Users_controller::class,'user_login']);

/* forget password */

Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
Route::post('reset_password-post', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');

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
Route::get('/up_article/{id_article}/{article_name}',[Stock_controller::class,'update_articles_view']);
Route::post('/article_update/{id_article}',[Stock_controller::class,'update_articles_stock']);
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

/* log out */
Route::get('/u/logout',[Users_controller::class,'user_logout']);
Route::get('/u/users',[Users_controller::class,'users']);
require __DIR__.'/auth.php';
