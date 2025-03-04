<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'profile_id',
        
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function savingGoals()
    {
        return $this->hasMany(SavingGoal::class);
    }
}
