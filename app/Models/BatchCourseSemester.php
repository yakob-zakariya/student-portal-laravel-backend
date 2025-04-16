<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BatchCourseSemester extends Pivot
{
    protected $fillable = ['course_id', 'batch_id', 'semester_id'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }


    public function teacherAssignments()
    {
        return $this->hasMany(TeacherAssignment::class);
    }
}
