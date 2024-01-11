<?php

namespace App\Http\Controllers;

use App\Models\Product; // Productモデルを現在のファイルで使用できるようにするための宣言
use App\Models\Company; // Companyモデルを現在のファイルで使用できるようにするための宣言
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   

    public function index(Request $request)
{
     // 企業名の一覧を取得
     $companies = Company::pluck('company_name', 'id');
    // Productモデルに基づいてクエリビルダを初期化
    $query = Product::query();
    // この行の後にクエリを逐次構築していきます。
    // そして、最終的にそのクエリを実行するためのメソッド（例：get(), first(), paginate() など）を呼び出すことで、データベースに対してクエリを実行します。

    // 商品名の検索キーワードがある場合、そのキーワードを含む商品をクエリに追加
    if($search = $request->search){
        $query->where('product_name', 'LIKE', "%{$search}%");
    }
    // 企業名が選択されている場合、その企業に絞り込み
    if ($companyId = $request->company_id) {
        $query->where('company_id', $companyId);
    }

    // 上記の条件(クエリ）に基づいて商品を取得し、10件ごとのページネーションを適用
    $products = $query->paginate(10);

    // 商品一覧ビューを表示し、取得した商品情報をビューに渡す
    return view('products.index', [
        'products' => $products,
        'companies' => $companies,
    ]);
}









    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::all();
        return view('products.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required', //requiredは必須
            'company_id' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'comment' => 'nullable', //'nullable'はそのフィールドが未入力でもOK
            'img_path' => 'nullable',
        ]);

        // 新しく商品を作る。そのための情報はリクエストから取得
        $product = new Product([
            'product_name' => $request->get('product_name'),
            'company_id' => $request->get('company_id'),
            'price' => $request->get('price'),
            'stock' => $request->get('stock'),
            'comment' => $request->get('comment'),
        ]);

        // リクエストに画像が含まれている場合、その画像を保存
        if($request->hasFile('img_path')){ 
            $filename = $request->img_path->getClientOriginalName();
            $filePath = $request->img_path->storeAs('public/products', $filename);
            //$filePath = $request->img_path->storeAs('products', $filename, 'public');上記に変更
            $product->img_path = 'storage/products/' . $filename;
            //$product->img_path = '/storage/' . $filePath; 上記に変更
            \Illuminate\Support\Facades\Log::info('Image saved: ' . $product->img_path);  // 保存処理が成功したかどうか確認するためにログに出力
        }

         // 作成したデータベースに新しいレコードとして保存
         $product->save();

         // 全ての処理が終わったら、商品一覧画面に戻る。
        return redirect('products');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        // 商品詳細画面を表示。その際に、商品の詳細情報を画面に渡す。
        return view('products.show', ['product' => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        // 商品編集画面で会社の情報が必要、全ての会社の情報を取得
        $companies = Company::all();
        // 商品編集画面を表示。その際に、商品の情報と会社の情報を画面に渡す。
        return view('products.edit', compact('product', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        // リクエストされた情報を確認して、必要な情報が全て揃っているかチェック。
        $request->validate([
            'product_name' => 'required',
            'price' => 'required',
            'stock' => 'required',
        ]);

        // 商品の情報を更新。
        $product->product_name = $request->product_name;
        //productモデルのproduct_nameをフォームから送られたproduct_nameの値に書き換える
        $product->price = $request->price;
        $product->stock = $request->stock;

        // 更新した商品を保存。
        $product->save();
        // モデルインスタンスである$productに対して行われた変更をデータベースに保存するためのメソッド。

        // 全ての処理が終わったら、商品一覧画面に戻る。
        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully');
        // ビュー画面にメッセージを代入した変数(success)を送る
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect('/products');
    }
       
  
}
