<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    // 他のモデルとの関連を定義する
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}


