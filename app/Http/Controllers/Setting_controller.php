<?php

namespace App\Http\Controllers;
use App\Models\Shop;
use App\Models\User_admin;
use App\Models\Setting;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Validator;
class Setting_controller extends Base_controller
{
    
    function setting_view(Request $request){
        $user = Auth::user();
        $shop_ses = session()->get('shop_ses');
        $data['shop'] = Shop::get_shop($shop_ses['shop_name']);
        if($data['shop'] !== null){
            $shop_ses = array('id_shop'=>$data['shop']->id_shop,'shop_name'=>$data['shop']->shop_name);
            parent::stock_ses($request,$shop_ses);
            $data['admin'] = User_admin::get_admin($data['shop']->id_shop,$user->id);
            $admins = User_admin::get_admins_list($data['shop']->id_shop,$user->id);
            $data['categories'] = parent::getCategories_shop($data['shop']->id_shop);
            return view('shop_setting',compact('admins'))->with('shop',$data['shop'])->with('admin',$data['admin'])->with('categories',$data['categories']);
        }else{
            return redirect('/');
        }
    }
    function change_name(Request $request){
        if (Gate::allows('isAdmin')){
            $shop_ses = session()->get('shop_ses');
            $validation = Validator::make($request->all(), [
                'shop_name' => 'required | unique:shops,shop_name'
            ]);
            if($validation->fails()){
                $name_error = $validation->errors()->first('shop_name');
                return redirect("/".$shop_ses['shop_name']."/setting")->with('name_error',$name_error);
            }else{
                $shop = Shop::get_shop($shop_ses['shop_name']);
                Setting::shop_old_info($shop);
                Setting::update_shop($shop->id_shop,"shop_name",$request->shop_name);
                $new_shop = Shop::get_shop($request->shop_name);
                session()->pull('shop_ses');
                $shop_ses = array('id_shop'=>$new_shop->id_shop,'shop_name'=>$request->shop_name);
                $request->session()->put('shop_ses',$shop_ses);
                return redirect("/".$shop_ses['shop_name']."/setting")->with('name_success','ショップの名前を変えることが出来ました。');
            }
        }else{
            return redirect("/".$shop_ses['shop_name']);
        }
    }
    function change_email(Request $request){
        if (Gate::allows('isAdmin')){
            $shop_ses = session()->get('shop_ses');
            $validation = Validator::make($request->all(), [
                'email' => 'required | email | confirmed | unique:shops,shop_email',
                'email_confirmation' => 'required | email'
            ]);
            if($validation->fails()){
                    $email_error =  $validation->errors()->first('email');
                    $cemail_error = $validation->errors()->first('email_confirmation');
                    return redirect("/".$shop_ses['shop_name']."/setting")->with('email_error',$email_error . $cemail_error);
            }else{
                $t_email = trim($request->email);
                $shop = Shop::get_shop($shop_ses['shop_name']);
                Setting::shop_old_info($shop);
                Setting::update_shop($shop->id_shop,"shop_email",$t_email);
                return redirect("/".$shop_ses['shop_name']."/setting")->with('email_success','メールが変更されました。');
            }
        }else{
            return redirect("/".$shop_ses['shop_name']);
        }
    }
    function change_phone(Request $request){
        if (Gate::allows('isAdmin')){
            $shop_ses = session()->get('shop_ses');
            $validation = Validator::make($request->all(), [
                'shop_phone' => 'required | max:19'
            ]);
            $phone_number_validation_regex = "^\d{2}(?:-\d{4}-\d{4}|\d{8}|\d-\d{3,4}-\d{4})$^";
            $preg = preg_match($phone_number_validation_regex, $request->shop_phone);
            if($preg == 0){
                return redirect("/".$shop_ses['shop_name']."/setting")->with('phone_error','電話番号は正しくありませんでした。');
            }else{
                if($validation->fails()){
                    $phone_error = $validation->errors()->first('shop_phone');
                    return redirect("/".$shop_ses['shop_name']."/setting")->with('phone_error',$phone_error);
                }else{
                    $shop = Shop::get_shop($shop_ses['shop_name']);
                    Setting::shop_old_info($shop);
                    Setting::update_shop($shop->id_shop,"phone_number",$request->shop_phone);
                    return redirect("/".$shop_ses['shop_name']."/setting")->with('phone_success','電話番号が変更されました。');
                }
            }
        }else{
            return redirect("/".$shop_ses['shop_name']);
        }
    }
    function change_web(Request $request){
        if (Gate::allows('isAdmin')){
            $shop_ses = session()->get('shop_ses');
            $validation = Validator::make($request->all(), [
                'new_web' => 'required'
            ]);
            if($validation->fails()){
                $web_error = $validation->errors()->first('new_web');
                return redirect("/".$shop_ses['shop_name']."/setting")->with('web_error',$web_error);
            }else{
                $shop = Shop::get_shop($shop_ses['shop_name']);
                Setting::shop_old_info($shop);
                Setting::update_shop($shop->id_shop,"shop_site",$request->new_web);
                return redirect("/".$shop_ses['shop_name']."/setting")->with('web_success','ウェブサイトが変更されました。');
            }
        }else{
            return redirect("/".$shop_ses['shop_name']);
        }
    }
    function search_user(Request $request){
        $user = Auth::user();
        $data['val'] = Setting::get_users($request->name,$user->email);
        echo json_encode($data);
    }
    function add_admin(Request $request){
        if (Gate::allows('isAdmin')){
            $shop_ses = session()->get('shop_ses');
            $validation = Validator::make($request->all(), [
                'email' => [
                    'required',
                    'email',
                    'exists:users,email',
                    Rule::unique('user_admins')->where(function ($query) {
                        $shop_ses = session()->get('shop_ses');
                        return $query->where('id_shop','=', $shop_ses['id_shop']);
                    })
                ],
            ]);
            if($validation->fails()){
                $add_error = $validation->errors()->first('email');
                return redirect("/".$shop_ses['shop_name']."/setting")->with('add_error','email invalid or email not used in kaibai or this email is already admin');;
            }else{
                $shop = Shop::get_shop($shop_ses['shop_name']);
                $user = Setting::get_user($request->email);
                Setting::insert_admin($user->id,$shop->id_shop);
                return redirect("/".$shop_ses['shop_name']."/setting")->with('add_success','管理者を加えることができました。');
            }
        }else{
            return redirect("/".$shop_ses['shop_name']);
        }
    }
    function adm_del(Request $request){
        if (Gate::allows('isShopSuperAdmin')){
            $shop_ses = session()->get('shop_ses');
            Setting::del_admin($shop_ses['id_shop'],$request->user);
            return redirect("/".$shop_ses['shop_name']."/setting")->with('add_success','管理者が削除されました。');
        }else{
            return redirect("/".$shop_ses['shop_name']."/setting")->with('del_error','管理者を削除出来るのはショップを作成した会員だけです。');
        }
    }
    function add_commercial(Request $request){
        $request->validate([
            'title' => 'max:50',
        ]);
        $shop_ses = session()->get('shop_ses');
        $path = parent::cover_folders($shop_ses['shop_name']);
        $file_path = parent::image_cm_upload($request->image,$path);
        Setting::add_cover_cm($shop_ses['id_shop'],$request->title,$file_path);
        echo json_encode('success');
    }
}
