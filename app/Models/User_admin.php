<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class User_admin extends Model
{
    use HasFactory;
    public $timestamps = false;

    
    public static function get_admin($id_shop,$id){
        $sql="SELECT * FROM user_admins WHERE id_shop='%s' AND id ='%s'";
		$admins = DB::select(sprintf($sql,$id_shop,$id));
        return $admins;
    }
    public static function get_admins_output($id,$admins){
        $output = '';
        foreach ($admins as $adm){
			if($id !== $adm->id){
			$output.='<div class="admins">
                    <div class="administrator">
                        <div class="admin-image">
                            <div class="online-status"></div>
                            <img src="'.asset($adm->logo_type).'" />
                        </div>
                        <div class="admin-name">'.$adm->firstname.' '.$adm->lastname.'</div>
                    </div>
                </div>';
            }
        }
        return $output;
    }
    public static function get_admins_list($id_shop,$id){
        $admins = User_admin::where('id_shop','=',$id_shop)
                        ->where('id','!=',$id)->get();
        return $admins;
    }
}
