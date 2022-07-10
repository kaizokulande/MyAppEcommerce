<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use App\Mail\PlanMail;

class SubscriptionController extends Controller
{
    public function view_subscription(){
        $subscriptions = Subscription::all();
        return view("subscription",compact('subscriptions'));
    }
    public function view_subscribed(){
        $subscriptions = Subscription::all();
        return view("subscription",compact('subscriptions'));
    }
    public function subscribe(Request $request){
        if (Auth::check()) {
            $user = Auth::user();
            DB::beginTransaction();
            try{
                $subscription = (Subscription::where('id', $request->subs_id)->get())->first();
                $now = Carbon::now();
                $limitdate = $now->addDays($subscription->day_bonus);
                if($subscription->price_notice == '毎月'){
                    $limitdate  = $limitdate->addMonth();
                }else if($subscription->price_notice == '毎年'){
                    $limitdate = $limitdate->addMonths(12);
                }
                $now = Carbon::now();
                $sql = "INSERT INTO user_shop_subscriptions(id_subscription,id_user,id_shop,validity,subscription_date,limit_date) VALUES('%s','%s',0,1,'%s','%s')";
                $sql = DB::insert(sprintf($sql,$subscription->id,Auth::id(),$now,$limitdate));
                $mail_limit_date = strftime('%Y %B %d %A', strtotime($limitdate));
                Mail::to($user->email)->send(new PlanMail('プラン登録ありがとうございます！',$user->firstname,$user->lastname,$mail_limit_date,$subscription->subs_name));
                DB::commit();
                return redirect()->route('plans_subscribed')->with('success','プレミアムになりました。');
            }catch(\Throwable $e){
                DB::rollback();
                /* 'エラーがありました！！' */
                return redirect()->route('plans')->with('error_subs',$e);
            }
        }else{
            return redirect()->route('plans')->with('error_log','Please login first Or Register if no account');
        }
    }
    public function subscribe_update(Request $request){
        if (Auth::check()) {
            DB::beginTransaction();
            $user = Auth::user();
            try {
                $subscription = (Subscription::where('id', $request->subs_id)->get())->first();
                $now = Carbon::now();
                $limitdate = $now->addDays($subscription->day_bonus);
                if($subscription->price_notice == '毎月'){
                    $limitdate  = $limitdate->addMonth();
                }else if($subscription->price_notice == '毎年'){
                    $limitdate = $limitdate->addMonths(12);
                }
                $now = Carbon::now();
                $user_subscription = collect(\DB::table('user_shop_subscriptions')->where('id_user','=',$user->id)->get())->first();
                DB::table('user_shop_subscriptions')
                            ->where('id_user', $user->id)
                            ->update(['id_subscription' => $subscription->id,
                            'validity'=>$user_subscription->validity,
                            'subscription_date'=>$now,
                            'limit_date'=>$limitdate,
                        ]);
                DB::commit();
                Mail::to($user->email)->send(new PlanMail('プラン登録ありがとうございます！',$user->firstname,$user->lastname,$limitdate,$subscription->subs_name));
                return redirect()->route('plans_subscribed')->with('success','プラン登録に成功しました。');
            } catch (\Throwable $th) {
                DB::rollback();
                return redirect()->route('plans')->with('error_subs','エラーがありました！！');
            }
        }else{
            return redirect()->route('plans')->with('error_log','プランに登録する前にログインしてください。');
        }
    }
}
