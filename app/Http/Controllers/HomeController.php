<?php

namespace App\Http\Controllers;

use App\Models\Product; // Productモデルを現在のファイルで使用できるようにするための宣言
use App\Models\Company; // Companyモデルを現在のファイルで使用できるようにするための宣言
//追加use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {

         /* テーブルから全てのレコードを取得する */
           //$companies = Companies::query();下で試してみる
           $companies = Company::query();


        /* キーワードから検索処理 */
        $keyword = $request->input('keyword');
        if(!empty($keyword)) {//$keyword　が空ではない場合、検索処理を実行します
            $companies->where('company_name', 'LIKE', "%{$keyword}%")
            ->orwhereHas('products', function ($query) use ($keyword) {
                $query->where('product_name', 'LIKE', "%{$keyword}%");
            });

        }
        /* デバッグ情報表示 */
        dd($companies->toSql(), $companies->getBindings());

        /* ページネーション */
        $posts = $companies->paginate(5);

        return view('products.index', ['posts' => $posts]);
        foreach ($posts as $post) {
            dump($post->img_path);
        }

    }

}