<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'address',
        'phone_number',
        'email',
        'manual_book'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
