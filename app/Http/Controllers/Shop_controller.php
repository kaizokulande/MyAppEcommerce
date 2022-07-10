<?php

namespace App\Http\Controllers;
use App\Models\Shop;
use App\Models\User_admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\CreationShopMail;

class Shop_controller extends Base_controller
{
    function create_chop(Request $request){
        if(Auth::check()){
            if(Gate::allows('canCreateshopNotsubscribed')){
                $request->validate([
                    'shop_name' => 'required | unique:shops,shop_name',
                    'phone' => 'required','max:20',
                    'email' => 'required | email | unique:shops,shop_email'
                ]);
                $phone_number_validation_regex = "^\d{2}(?:-\d{4}-\d{4}|\d{8}|\d-\d{3,4}-\d{4})$^";
                $preg = preg_match($phone_number_validation_regex, $request->phone);
                if($preg == 0){
                    return redirect('createshop')->with('phone_error','電話番号は正しくありませんでした。');
                }
                $t_email = trim($request->email);
                $user = Auth::user();
                DB::beginTransaction();
                try{
                    Shop::create_shop($user->id,$request->shop_name,$request->phone,$t_email);
                    DB::table('user_shop_subscriptions')
                                ->where('id_user', $user->id)
                                ->update(['validity' => 0]);
                    DB::commit();
                    Mail::to($user->email)->send(new CreationShopMail('ショップ作成おめでとうございます！',$user->firstname,$user->lastname,$request->shop_name));
                    return redirect()->route('createshop')->with('success','Shop created successfuly');
                }catch(\Exception $e){
                    DB::rollback();
                    return redirect()->route('plans')->with('error_subs','エラーがありました！！');
                }
            }else{
                return redirect('createshop')->with('error_log','ショップを作成されています。プランに登録してください。');
            }
        }else{
            return redirect()->route('createshop')->with('error_log','Please login first Or Register if you have no account');
        }
    }
    /* get shop list */
    public static function getshops($id){
        $sql="SELECT * FROM user_admin_shop WHERE id='%s'";
        $shops = DB::select(sprintf($sql,$id));
        return $shops;
    }
    
    function shop_view(Request $request){
        $user = Auth::user();
        $shop_ses = session()->get('shop_ses');
        $data['shop'] = Shop::get_shop($request->shop_name);
        if($data['shop'] !== null){
            if($user !=null){
                $data['admin'] = User_admin::get_admin($data['shop']->id_shop,$user->id);
                if($data['admin'] !=null){
                    $data['admins'] = User_admin::get_admins_list($data['shop']->id_shop,$user->id);
                    $shop_ses = array('id_shop'=>$data['shop']->id_shop,'shop_name'=>$data['shop']->shop_name);
                    $request->session()->put('shop_ses',$shop_ses);
                }
            }
            if($request->page==null){
                $request->page =1;
            }
            $articles_length = Shop::get_count_articles($data['shop']->id_shop);
            $articles_per_page = 9;
            $data['cover'] = Shop::get_commercial();
            if($data['cover'] == null) $data['cover']='not defined';
            $data['next_page'] = $request->page+1;
		    $data['prev_page'] = $request->page-1;
            $data['current_page'] = $request->page;
		    $data['end'] = ceil($articles_length / $articles_per_page);
		    $data['article_total'] = $articles_length;
            $data['page'] =intval($request->page);
            $data['total_page'] = ceil($articles_length / $articles_per_page);
            $depart = ($data['page']-1)*$articles_per_page;
            $data['categories'] = parent::getCategories_shop($data['shop']->id_shop);
            $data['articles'] = Shop::get_articles($data['shop']->id_shop,$depart,$articles_per_page);
            return view('shop',$data);
        }else{
            return redirect('/');
        }
    }
    function add_article_view(Request $request){
        $shop_ses = session()->get('shop_ses');
        $data['shop'] = Shop::get_shop($shop_ses['shop_name']);
        if($data['shop'] !== null){
            $user = Auth::user();
            $data['admin'] = User_admin::get_admin($data['shop']->id_shop,$user->id);
            $data['admins'] = User_admin::get_admins_list($data['shop']->id_shop,$user->id);
            $data['categories_articles'] = parent::getCategories();
            $data['categories'] = parent::getCategories_shop($data['shop']->id_shop);
            return view('shop_add_article',$data);
        }else{
            return redirect('/');
        }
    }
    function upload_article(Request $request){
        $shop = Shop::get_shop($request->shop_name);
        if(Auth::check()){
            $user = Auth::user();
            $request->validate([
                'image' => 'required | mimes:jpeg,png',
                'name' => 'required',
                'price' => 'required | gte:100 | lte:1000001',
                'size' => 'required',
                'quantity' => 'required | gte:1',
            ]);
            $description = parent::space_nl($request->description);
            $imgfile = $request->image;
            $filename = base64_encode($imgfile.''.time()).'.'.$imgfile->extension();
            $b_path = parent::big_folders($shop->shop_name);
            $big_path = $b_path.''.$filename;
            $s_path = parent::small_folders($shop->shop_name);
            $small_path = $s_path.''.$filename;
            parent::image_upload($imgfile,$big_path,$small_path);
            $sql= "INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states) VALUES('%s','%s','%s','%s','%s','%s','%s','%s',NOW(),'%s',calctotal('%s','%s'),'%s','%s','on sale')";
            $sql = DB::insert(sprintf($sql,$user->id,$shop->id_shop,$request->categorie,$request->name,$request->color,$request->size,$request->quantity,$request->price,$description,$request->price,$request->quantity,$big_path,$small_path));
            $success_mess = 'Your article has benn uploaded successfuly. Click here if you wat to see it.';
            return redirect('/'.$shop->shop_name.'/addarticle')->with('success',$success_mess);
        }else{
            return redirect('shop/'.$shop->shop_name)->with('error_log','Please login first Or Register if no account');
        }
    }
    function view_shop_article(Request $request){
        $shop_ses = session()->get('shop_ses');
        $user = Auth::user();
        $data['shop'] = Shop::get_shop($request->shopname);
        if($data['shop'] !== null){
            if($user !=null){
                $data['admin'] = User_admin::get_admin($data['shop']->id_shop,$user->id);
                if($data['admin'] !=null){
                    $data['admins'] = User_admin::get_admins_list($data['shop']->id_shop,$user->id);
                }
            }
            $data['categories'] = parent::getCategories_shop($data['shop']->id_shop);
            $data['article'] = Shop::get_shop_article($request->id_article,$request->article_name,$data['shop']->id_shop);
            if($data['article']==null){
                return redirect('shop/'.$request->shopname);
            }else{
                return view('shop_article',$data);
            }
        }else{
            return redirect('/');
        }
    }

    public function search_view(Request $request){
        $user = Auth::user();
        $shop_ses = session()->get('shop_ses');
        $data['shop'] = Shop::get_shop($request->shop_name);
        if($data['shop'] !== null){
            if($user !=null){
                $data['admin'] = User_admin::get_admin($data['shop']->id_shop,$user->id);
                if($data['admin']!=null){
                    $data['admins'] = User_admin::get_admins_list($data['shop']->id_shop,$user->id);
                }
            }
            if($request->page==null){
                $request->page = 1;
            }
            $articles_length = Shop::count_search($data['shop']->id_shop,$request->search);
            $articles_per_page = 9;
            $data['search'] = $request->search;
            $data['next_page'] = $request->page+1;
		    $data['prev_page'] = $request->page-1;
            $data['current_page'] = $request->page;
		    $data['end'] = ceil($articles_length / $articles_per_page);
		    $data['article_total'] = $articles_length;
            $data['page'] =intval($request->page);
            $data['total_page'] = ceil($articles_length / $articles_per_page);
            $depart = ($data['page']-1)*$articles_per_page;
            $data['categories'] = parent::getCategories_shop($data['shop']->id_shop);
            $data['articles'] = Shop::search_articles($data['shop']->id_shop,$data['search'],$depart,$articles_per_page);
            return view('shop_search',$data);
        }else{
            return redirect('/');
        }
    }
    public function categorie_view(Request $request){
        $user = Auth::user();
        $shop_ses = session()->get('shop_ses');
        $data['shop'] = Shop::get_shop($request->shop_name);
        if($data['shop'] !== null){
            if($user !=null){
                $data['admin'] = User_admin::get_admin($data['shop']->id_shop,$user->id);
                if($data['admin'] !=null){
                    $data['admins'] = User_admin::get_admins_list($data['shop']->id_shop,$user->id);
                }
            }
            if($request->page==null){
                $request->page = 1;
            }
            $articles_length = Shop::count_categorie_articles($data['shop']->id_shop,$request->categorie);
            $articles_per_page = 9;
            $data['categorie'] = $request->categorie;
            $data['next_page'] = $request->page+1;
		    $data['prev_page'] = $request->page-1;
            $data['current_page'] = $request->page;
		    $data['end'] = ceil($articles_length / $articles_per_page);
		    $data['article_total'] = $articles_length;
            $data['page'] =intval($request->page);
            $data['total_page'] = ceil($articles_length / $articles_per_page);
            $depart = ($data['page']-1)*$articles_per_page;
            $data['categories'] = parent::getCategories_shop($data['shop']->id_shop);
            $data['articles'] = Shop::search_categorie_articles($data['shop']->id_shop,$request->categorie,$depart,$articles_per_page);
            return view('shop_categorie',$data);
        }else{
            return redirect('/');
        }
    }
}
