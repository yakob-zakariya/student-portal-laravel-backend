<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherAssignment extends Model
{
    protected $fillable = [
        'teacher_id',
        'batch_course_semester_id',
        'section_id'
    ];


    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function batchCourseSemester()
    {
        return $this->belongsTo(BatchCourseSemester::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    // Access course directly

    public function course()
    {
        return $this->batchCourseSemester->course;
    }
}
