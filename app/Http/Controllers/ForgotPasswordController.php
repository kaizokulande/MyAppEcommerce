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
            return view('reset_password');
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
                'password' => 'required|string|min:4|confirmed',
                'password_confirmation' => 'required'
            ]);
            $user = User::where('email', $request->email)
                        ->update(['password' => Hash::make($request->password)]);
            return redirect('/login')->with('message', 'Mot de passe réinitialisé avec succes!');
        }
}
