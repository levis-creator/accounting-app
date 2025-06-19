<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory, HasUuids;
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
