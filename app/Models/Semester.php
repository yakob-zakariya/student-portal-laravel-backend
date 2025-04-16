<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $fillable = ['name', 'academic_year',];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    // A semester can have many courses and batches
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'batch_course_semester')
            ->using(BatchCourseSemester::class)
            ->withPivot('batch_id');
    }

    public function batches()
    {
        return $this->belongsToMany(Batch::class, 'batch_course_semester')
            ->using(BatchCourseSemester::class)
            ->withPivot('course_id');
    }
}
