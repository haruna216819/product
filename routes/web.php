<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Route::get('/products', 'ProductController@index')->name('products.index');//GPIにて

Route::get('/products', function () {
    
    if (Auth::check()) {
        // ログイン状態ならば
        return redirect()->route('products.index');
        // 商品一覧ページ（ProductControllerのindexメソッドが処理）へリダイレクトします
    } else {
        // ログイン状態でなければ
        return redirect()->route('login');
        //　ログイン画面へリダイレクトします
    }
});

Auth::routes();


Route::group(['middleware' => 'auth'], function () {
    Route::resource('products', ProductController::class);
});
Route::get('/', 'App\Http\Controllers\HomeController@index')->name('crud.index'); /* 一覧表示 */