<?php

namespace App\Models;

use App\Policies\CommentPolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'supervision_id',
        'comment',
        'is_read',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($comment) {
            $user = Auth::user();
            if ($user && $user->role === 'teacher') {
                self::where('supervision_id', $comment->supervision_id)
                    ->whereHas('user', function ($query) {
                        $query->where('role', 'principal');
                    })
                    ->update(['is_read' => 'y']);
            } elseif ($user && $user->role === 'principal') {
                self::where('supervision_id', $comment->supervision_id)
                    ->whereHas('user', function ($query) {
                        $query->where('role', 'teacher');
                    })
                    ->update(['is_read' => 'y']);
            }

        });
    }

    // protected $policies = [
    //     Comment::class => CommentPolicy::class,
    // ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supervision()
    {
        return $this->belongsTo(Supervision::class);
    }

}
