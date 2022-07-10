<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;
class Setting extends Model
{
    use HasFactory;
    public static function shop_old_info($shop){
        $sql ="INSERT INTO shop_oldinfo(id,id_shop,shop_name,phone_number,shop_email,change_date,descriptions,shop_site,shop_logo,shop_picture) VALUES('%s','%s','%s','%s','%s',now(),'%s','%s','%s','%s')";
        $sql = DB::insert(sprintf($sql,$shop->id,$shop->id_shop,$shop->shop_name,$shop->phone_number,$shop->shop_email,$shop->descriptions,$shop->shop_site,$shop->shop_logo,$shop->shop_picture));
    }
    public static function update_shop($id_shop,$col_name,$value){
        $sql ="UPDATE shops SET %s = '%s' WHERE id_shop=%s";
        $sql = DB::insert(sprintf($sql,$col_name,$value,$id_shop));
    }
    public static function get_users($name,$user_email){
        $users = DB::table('users')
                        ->where('email','LIKE','%'.$name.'%')
                        ->where('email','!=',$user_email)
                        ->limit(4)
                        ->get();
        return $users;
    }
    /* INSERT INTO user_shop VALUES('1','1','admin'); */
    public static function get_user($email){
        $users = collect(\DB::table('users')
                        ->where('email','=',$email)
                        ->limit(4)
                        ->get())->first();
        return $users;
    }
    public static function insert_admin($id,$id_shop){
        DB::table('user_shop')->insert([
            'id' => $id,
            'id_shop' => $id_shop,
            'states' => 'admin'
        ]);
    }
    public static function del_admin($id_shop,$id){
        DB::table('user_shop')
            ->where('id_shop','=',$id_shop)
            ->where('id','=',$id)
            ->delete();
    }
    public static function add_cover_cm($id_shop,$title,$image){
        DB::table('commercials')->insert([
            'id_shop' => $id_shop,
            'dates' => NOW(),
            'title' => $title,
            'big_image' => $image
        ]);
    }
}
