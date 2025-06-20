<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'category_id',
        'month',
        'limit_amount',
    ];
    /** @use HasFactory<\Database\Factories\BudgetFactory> */
    use HasFactory, HasUuids;
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
