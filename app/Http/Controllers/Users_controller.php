<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Base_controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\User_admin;
use Carbone\Carbone;
use Cache;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Mail;
use App\Mail\KaibaiMail;
use App\Mail\ContactMail;
use Illuminate\Support\Str;
use Hash;
class Users_controller extends Base_controller
{
    function user_register(DB $db,Request $req){
        $req->validate([
            'first_name' => 'required','regex:/^[a-zA-Z]+$/',
            'last_name' => 'required','regex:/^[a-zA-Z]+$/',
            'birth_date' => 'required | after_or_equal:1930-01-01 | before:12 years ago',
            'email' => 'required | email | unique:users,email',
            'gender' => 'required',
            'password' => 'required | min:4 | confirmed',
            'password_confirmation' => 'required | min:8'
        ]);
        $db::beginTransaction();
        try {
            $first_name_regex = "^[a-zA-Z]+$^";
            $preg_first_name = preg_match($first_name_regex, $req->first_name);
            $preg_last_name = preg_match($first_name_regex, $req->last_name);
            if($preg_first_name == 0 || $preg_last_name == 0){
                return redirect('register')->with('name_romaji_error','Ecrivez bien votre nom');
            }
            $t_email = trim($req->email);
            $t_password = bcrypt(trim($req->password));
            $logo_type = parent::get_logo_user($req->gender);
            $sql ="INSERT INTO users(firstname,lastname,birthday,gender,email,password,logo_type,sign_date,delete_date,spec,status) VALUES('%s','%s','%s','%s','%s','%s','%s',now(),NULL,'client',1)";
            $sql = $db::insert(sprintf($sql,$req->first_name,$req->last_name,$req->birth_date,$req->gender,$t_email,$t_password,$logo_type));
            $success_mess = 'Félicitation! Vous êtes inscrit!';
            $db::commit();
            return redirect('register')->with('success',$success_mess);
        } catch (\Throwable $th) {
            $db::rollback();
            return redirect('register')->with('confirm_error','Un erreur s\'est produit.');
        }
    }

    function user_login(Request $login_req){
        $credentials = $login_req->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        Cart::destroy();
        $t_email = trim($login_req->email);
        $user = User::where('email','=',$t_email)->first();
        if($user !=null){
            if($user->status==1){
                if (Auth::attempt($credentials)) {
                    return redirect()->intended('/');
                }
            }else{
                return back()->withErrors([
                    'error_conf_mail' => 'Veuiller confirmer votre Email.',
                ]);
            }
            return back()->withErrors([
                'error_pass' => 'Votre Email ou votre mot de passe n\'est pas valide',
            ]);
        }else{
            return back()->withErrors([
                'error_pass' => 'Votre Email ou votre mot de passe n\'est pas valide',
            ]);
        }
    }
    
}
