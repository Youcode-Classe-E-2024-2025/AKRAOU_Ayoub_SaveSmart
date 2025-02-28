<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'avatar',
        'account_id',
    ];

    public function account()
    {
      return $this->belongsTo(Account::class);
    }

    public function transactions()
    {
      return $this->hasMany(Transaction::class);
    }
}
