<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'classroom_period_teacher_subject_relation_id',
        'name',
        'note',
        'document'
    ];

    public function classroomPeriodTeacherSubjectRelation()
    {
        return $this->belongsTo(ClassroomPeriodTeacherSubjectRelation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}