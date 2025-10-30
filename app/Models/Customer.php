<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model {
    protected $fillable = ['name', 'email', 'mobile', 'user_id'];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function invoices(): HasMany {
        return $this->hasMany( Invoice::class );
    }
}