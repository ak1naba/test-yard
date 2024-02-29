<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    // Заполняемы поля
    protected $fillable = ['balance'];

    public function getUser()
    {
        return $this->belongsTo(User::class. 'user_id');
    }

    public function getOperations()
    {
        return $this->hasMany(Operation::class);
    }
}
