<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'note',
        'name',
        'is_default'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->is_default === 'y') {
                // Set semua baris lain menjadi 'n' jika baris ini 'y'
                static::where('id', '!=', $model->id)
                    ->update(['is_default' => 'n']);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
