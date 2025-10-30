<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    protected $fillable = ['name', 'price', 'unit', 'user_id', 'category_id'];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}