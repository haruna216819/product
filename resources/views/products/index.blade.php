@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">商品一覧画面</h1>
    <!-- 検索機能 -->
    <div>
    <form action="{{ route('products.index') }}" method="GET">

    @csrf
    <!-- value 属性を追加して、初期値を空に設定 -->
        <input type="text" name="search"  value="{{ request('search') }}" placeholder="検索キーワード" style="margin-right: 20px">
         <!-- 企業名のセレクトボックス -->
    <select name="company_id" style="margin-right: 20px">
        <option value="" >メーカー名</option>
        @foreach($companies as $companyId => $companyName)
            <option value="{{ $companyId }}">{{ $companyName }}</option>
        @endforeach
    </select>
        <input type="submit" value="検索">
    </form>
    </div>

    <div class="products mt-5">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>商品画像</th>
                    <th>商品名</th>
                    <th>価格</th>
                    <th>在庫数</th>
                    <th>メーカー名</th>
                    <th> <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">新規登録</a></th>
                </tr>
            </thead>
            <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->id}}</td>
                    <td><img src="{{ asset($product->img_path) }}" alt="商品画像" width="100"></td> 
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>
                        @if ($product->company)
                            {{ $product->company->company_name }}
                        @else
                            <!-- もし $product->company が null の場合の処理 -->
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('products.show', $product) }}" class="btn btn-info btn-sm mx-1">詳細</a>
                        <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm mx-1">削除</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{ $products->links() }}
</div>
@endsection
