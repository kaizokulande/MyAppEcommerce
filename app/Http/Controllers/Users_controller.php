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
use Illuminate\Support\Facades\Mail;
use App\Mail\KaibaiMail;
class Users_controller extends Base_controller
{
    function user_register(Request $req){
        $req->validate([
            'first_name' => 'required','regex:/^[a-zA-Z]+$/',
            'last_name' => 'required','regex:/^[a-zA-Z]+$/',
            'birth_date' => 'required | after_or_equal:1930-01-01 | before:12 years ago',
            'email' => 'required | email | unique:users,email',
            'gender' => 'required',
            'password' => 'required | min:8 | confirmed',
            'password_confirmation' => 'required | min:8'
        ]);
        $first_name_regex = "^[a-zA-Z]+$^";
        $preg_first_name = preg_match($first_name_regex, $req->first_name);
        $preg_last_name = preg_match($first_name_regex, $req->last_name);
        if($preg_first_name == 0 || $preg_last_name == 0){
            return redirect('register')->with('name_romaji_error','ロマ字で書いてください。');
        }
        $t_email = trim($req->email);
        $t_password = bcrypt(trim($req->password));
        $logo_type = parent::get_logo_user($req->gender);
        $sql ="INSERT INTO users(firstname,lastname,birthday,gender,email,password,logo_type,sign_date,delete_date,spec,status) VALUES('%s','%s','%s','%s','%s','%s','%s',now(),NULL,'client',0)";
        $sql = DB::insert(sprintf($sql,$req->first_name,$req->last_name,$req->birth_date,$req->gender,$t_email,$t_password,$logo_type));
        $user =User::where('email','=',$t_email)->first();
        $code = bcrypt($t_email);
        $sql = "INSERT INTO confirm_users(id_user,code) VALUES('%s','%s')";
        $sql = DB::insert(sprintf($sql,$user->id,$code));
        /*  /register/email_confirmation/{code} */
        Mail::to($t_email)->send(new KaibaiMail('会員登録ありがとうございました！',$req->first_name,$req->last_name,$code));
        $success_mess = 'You have registered successfuly.';
        return redirect('register')->with('success',$success_mess);
    }
    function confirm_email(Request $request){
        $confirm_user =collect(\DB::table('confirm_users')
                ->where('code','=',$request->code)->get())->first();
        if($confirm_user != null){
            DB::table('users')
              ->where('id', $confirm_user->id_user)
              ->update(['status' => 1]);
            $user = User::where('id','=',$confirm_user->id_user)->first();
            return redirect('login');
        }else{
            return redirect('register')->with('confirm_error','メールがまだ登録されていません。');
        }
    }
    function user_login(Request $login_req){
        $credentials = $login_req->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        $t_email = trim($login_req->email);
        $user =User::where('email','=',$t_email)->first();
        if (Auth::attempt($credentials)) {
            if($user->status==1){
                return redirect()->intended('/',);
            }else{
                return back()->withErrors([
                    'error_conf_mail' => 'メールがまだ確認されていません。登録メールでリンクをクリックしてください。',
                ]);
            }
        }
        return back()->withErrors([
            'error_pass' => 'your Passord or Email is not valid.',
        ]);
    }
    
}
