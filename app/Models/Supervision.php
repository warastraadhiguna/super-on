<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supervision extends Model
{
    use HasFactory;
    protected $fillable = [
        'classroom_period_teacher_subject_relation_id',
        'name',
        'note',
        'link',
        'document',
    ];

    public function classroomPeriodTeacherSubjectRelation()
    {
        return $this->belongsTo(ClassroomPeriodTeacherSubjectRelation::class, 'classroom_period_teacher_subject_relation_id');
    }
}
