<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseRegistration extends Pivot
{
    protected $table = 'course_registrations';
    protected $fillable = ['student_id', 'section_id', 'course_id', 'semester_id'];


    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
