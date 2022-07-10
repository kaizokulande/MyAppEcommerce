<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
class Article_controller extends Base_controller
{
    
     /* article uplaod */
     function upload_article(Request $request){
        $shop_name = null;
        if(Auth::check()){
            $user = Auth::user();
            $request->validate([
                'image' => 'required | mimes:jpeg,png',
                'name' => 'required',
                'price' => 'required | gte:100 | lte:100000001',
                'size' => 'required',
                'quantity' => 'required | gte:1',
            ]);
            $description = parent::space_nl($request->description);
            $imgfile = $request->image;
            $filename = base64_encode($imgfile.''.time()).'.'.$imgfile->extension();
            $b_path = parent::big_folders($shop_name);
            $big_path = $b_path.''.$filename;
            $s_path = parent::small_folders($shop_name);
            $small_path = $s_path.''.$filename;
            parent::image_upload($imgfile,$big_path,$small_path);
            $sql= "INSERT INTO articles(id,id_shop,id_categorie,article_name,color,sizes,quantity,price,creation_date,descriptions,total_price,large_images,small_images,states) VALUES('%s',0,'%s','%s','%s','%s','%s','%s',NOW(),'%s',calctotal('%s','%s'),'%s','%s','on sale')";
            $sql = DB::insert(sprintf($sql,$user->id,$request->categorie,$request->name,$request->color,$request->size,$request->quantity,$request->price,$description,$request->price,$request->quantity,$big_path,$small_path));
            $success_mess = 'Your article has benn uploaded successfuly. Click here if you wat to see it.';
            return redirect('sellarticle')->with('success',$success_mess);
        }else{
            return redirect('sellarticle')->with('error_log','Please login first Or Register if no account');
        }
    }
    /* get Articles */
    function get_articles($depart,$articles_per_page){
        $sql="SELECT * FROM articles WHERE states!='deleted' AND quantity!=0 ORDER BY creation_date DESC LIMIT %s,%s";
        $articles = DB::select(sprintf($sql,$depart,$articles_per_page));
        return $articles;
    }
    /* count articles */
    public function get_count_articles(){
        $sql="SELECT COUNT(*) AS total FROM articles WHERE states!='deleted' AND quantity !='0'";
        $count = collect(\DB::select($sql))->first();
        return $count->total;
    }
    /* get_article */
    public static function get_article($id_article){
        $sql="SELECT * FROM articles WHERE states!='deleted' AND id_article='%s'";
		$sql = sprintf($sql,$id_article);
		$article = collect(\DB::select($sql))->first();
        return $article;
    }
    public static function get_article_delete($id_article,$article_name){
        $sql="SELECT * FROM articles WHERE states!='deleted' AND id_article='%s' AND article_name='%s'";
		$sql = sprintf($sql,$id_article,$article_name);
		$article = collect(\DB::select($sql))->first();
        return $article;
    }
    public static function count_search($search){
        $total= collect(\DB::table('articles')
                        ->select(DB::raw('COUNT(*) as total'))
                        ->where('states','!=','deleted')
                        ->where('article_name','LIKE','%'.$search.'%')
                        ->get())->first();
        return $total->total;
    }
    public static function search_articles($search,$depart,$articles_per_page){
        $articles = DB::table('articles')
                        ->where('states','!=','deleted')
                        ->where('article_name','LIKE','%'.$search.'%')
                        ->orderBy('creation_date', 'desc')
                        ->offset($depart)
                        ->limit($articles_per_page)
                        ->get();
        return $articles;
    }
    public static function count_categorie_articles($categorie){
        $total= collect(\DB::table('articles_categories')
                        ->select(DB::raw('COUNT(*) as total'))
                        ->where('categorie','=',$categorie)
                        ->where('states','!=','deleted')
                        ->get())->first();
        return $total->total;
    }
    public static function search_categorie_articles($categorie,$depart,$articles_per_page){
        $articles = DB::table('articles_categories')
                        ->where('categorie','=',$categorie)
                        ->where('states','!=','deleted')
                        ->orderBy('creation_date', 'desc')
                        ->offset($depart)
                        ->limit($articles_per_page)
                        ->get();
        return $articles;
    }
    public static function get_article_name($id_article,$article_name){
        $sql="SELECT * FROM articles_categories WHERE states!='deleted' AND id_article='%s' AND article_name='%s'";
		$sql = sprintf($sql,$id_article,$article_name);
		$article = collect(\DB::select($sql))->first();
        return $article;
    }
    /* page articles index */
    function home(Request $request){
        if(session()->has('shop_sess')){
            session()->pull('shop_sess');
        }
        if($request->page==null){
            $request->page =1;
        }
        $articles_per_page = 10;
		$articles_length = $this->get_count_articles();
		/* $data['commercials'] = $this->news_model->get_commercial_index(); */
        $data['next_page'] = $request->page+1;
		$data['prev_page'] = $request->page-1;
        $data['current_page'] = $request->page;
		$data['end'] = ceil($articles_length / $articles_per_page);
		$data['article_total'] = $articles_length;
        $data['page'] =intval($request->page);
        $data['total_page'] = ceil($articles_length / $articles_per_page);
        $depart = ($data['page']-1)*$articles_per_page;
		$data['articles'] = $this->get_articles($depart,$articles_per_page);
		return view('kaibai',$data);
    }
    function view_article(Request $request){
        $data['article'] = $this->get_article_name($request->id_article,$request->article_name);
        if($data['article']==null){
            return redirect('/');
        }else{
            return view('article',$data);
        }
    }

    function view_search(Request $request){
        if($request->search == null){
            return redirect('/');
        }
        if($request->page==null){
            $request->page =1;
        }
        $articles_per_page = 10;
		$articles_length = $this->count_search($request->search);
		/* $data['commercials'] = $this->news_model->get_commercial_index(); */
        $data['next_page'] = $request->page+1;
		$data['prev_page'] = $request->page-1;
        $data['current_page'] = $request->page;
		$data['end'] = ceil($articles_length / $articles_per_page);
		$data['article_total'] = $articles_length;
        $data['page'] =intval($request->page);
        $data['total_page'] = ceil($articles_length / $articles_per_page);
        $data['search'] = $request->search;
        $depart = ($data['page']-1)*$articles_per_page;
		$data['articles'] = $this->search_articles($request->search,$depart,$articles_per_page);
		return view('kaibai_search',$data);
    }
    function view_categories(Request $request){
        if($request->categorie == null){
            return redirect('/');
        }
        if($request->page==null){
            $request->page =1;
        }
        $articles_per_page = 10;
		$articles_length = $this->count_categorie_articles($request->categorie);
		/* $data['commercials'] = $this->news_model->get_commercial_index(); */
        $data['next_page'] = $request->page+1;
		$data['prev_page'] = $request->page-1;
        $data['current_page'] = $request->page;
		$data['end'] = ceil($articles_length / $articles_per_page);
		$data['article_total'] = $articles_length;
        $data['page'] =intval($request->page);
        $data['total_page'] = ceil($articles_length / $articles_per_page);
        $data['categorie'] = $request->categorie;
        $depart = ($data['page']-1)*$articles_per_page;
		$data['articles'] = $this->search_categorie_articles($request->categorie,$depart,$articles_per_page);
		return view('kaibai_categorie',$data);
    }
}
