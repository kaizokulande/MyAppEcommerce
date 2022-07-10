<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        /* if subscribed or not */
        $id_user = Auth::id();
        Gate::define('isNotSubscribed', function ($id_user) {
            $subscription = collect(\DB::table('user_shop_subscriptions')->where('id_user','=',$id_user->id)->get())->first();
            if($subscription==null){
                return 'isNotSubscribed';
            }
        });
        Gate::define('isSubscribed', function ($id_user) {
            $subscription = collect(\DB::table('user_shop_subscriptions')->where('id_user','=',$id_user->id)->get())->first();
            if($subscription!=null){
                return 'isSubscribed';
            }
        });
        /* if has a shop or not */
        Gate::define('shopnotExist', function ($id_user) {
            $subscription = collect(\DB::table('user_shop_subscriptions')->where('id_user','=',$id_user->id)->where('validity','=',0)->get())->first();
            if($subscription==null){
                return 'shopnotExist';
            }
        });
        Gate::define('shopExist', function ($id_user) {
            $subscription = collect(\DB::table('user_shop_subscriptions')->where('id_user','=',$id_user->id)->where('validity','=',0)->get())->first();
            if($subscription!=null){
                return 'shopExist';
            }
        });
        /* admin or not */
        Gate::define('isAdmin', function ($id_user) {
            $shop_ses = session()->get('shop_ses');
            if($shop_ses!=null){
                $id_shop = $shop_ses['id_shop'];
                $admin = collect(\DB::table('user_admins')->where('id','=',$id_user->id)->where('id_shop','=',$id_shop)->get())->first();
                if($admin!=null){
                    return 'isAdmin';
                }
            }
        });
        /* the shop super admin or not */
        Gate::define('isShopSuperAdmin', function ($id_user) {
            $shop_ses = session()->get('shop_ses');
            if($shop_ses!=null){
                $id_shop = $shop_ses['id_shop'];
                $admin = collect(\DB::table('shops')->where('id','=',$id_user->id)->where('id_shop','=',$id_shop)->get())->first();
                if($admin!=null){
                    return 'isShopSuperAdmin';
                }
            }
        });
        
    }
}
