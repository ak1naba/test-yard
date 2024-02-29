<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    use HasFactory;

    protected $fillable = [
      'amount',
      'description',
      'type_operation'
    ];

    public function getWallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id');
    }

}
