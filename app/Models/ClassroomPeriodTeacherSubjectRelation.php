<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassroomPeriodTeacherSubjectRelation extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'principal_id',
        'assessment_score',
        'period_id',
        'classroom_id',
        'teacher_subject_relation_id',
        'note'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function teacherSubjectRelation()
    {
        return $this->belongsTo(TeacherSubjectRelation::class, 'teacher_subject_relation_id');
    }
}