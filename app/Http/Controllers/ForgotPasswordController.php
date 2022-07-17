<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Base_controller;
use Illuminate\Http\Request;
use DB; 
use Carbon\Carbon; 
use Illuminate\Support\Facades\Auth;
use App\Models\User; 
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgetPasswordMail;
use Hash;
use Illuminate\Support\Str;

class ForgotPasswordController extends Base_controller
{
     /**
       * Write code on Method
       *
       * @return response()
       */
      public function showForgetPasswordForm()
      {
         return view('forget_password');
      }
  
      /**
       * Write code on Method
       *
       * @return response()
       */
      public function submitForgetPasswordForm(Request $request)
      {
          $request->validate([
              'email' => 'required|email|exists:users',
          ]);
  
          $token = Str::random(64);
  
          DB::table('reset_passwords')->insert([
              'email' => $request->email, 
              'token' => $token, 
              'created_at' => Carbon::now()
            ]);
            Mail::to($request->email)->send(new ForgetPasswordMail($token));
  
          return back()->with('message', 'パスワードリセットリンクをメールで送信しました。');
      }
      /**
       * Write code on Method
       *
       * @return response()
       */
      public function showResetPasswordForm($token) { 
         return view('kaibai_reset_password', ['token' => $token]);
      }
  
      /**
       * Write code on Method
       *
       * @return response()
       */
      public function submitResetPasswordForm(Request $request)
      {
          $request->validate([
              'email' => 'required|email|exists:users',
              'password' => 'required|string|min:8|confirmed',
              'password_confirmation' => 'required'
          ]);
  
          $updatePassword = DB::table('reset_passwords')
                              ->where([
                                'email' => $request->email, 
                                'token' => $request->token
                              ])
                              ->first();
  
          if(!$updatePassword){
              return back()->withInput()->with('error', 'Invalid token!');
          }
  
          $user = User::where('email', $request->email)
                      ->update(['password' => Hash::make($request->password)]);
 
          DB::table('reset_passwords')->where(['email'=> $request->email])->delete();
  
          return redirect('/login')->with('message', 'あなたのパスワードは変更されました！');
      }
}
