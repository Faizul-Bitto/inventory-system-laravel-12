<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model {
    protected $fillable = ['total', 'discount', 'vat', 'payable', 'user_id', 'customer_id'];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function customers(): BelongsTo {
        return $this->belongsTo( Customer::class );
    }
}