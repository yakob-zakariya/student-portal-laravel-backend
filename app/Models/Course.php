<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    /** @use HasFactory<\Database\Factories\CourseFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'credit_hour',
    ];

    public function prerequisites()
    {
        return $this->belongsToMany(Course::class, 'course_prerequisite', 'course_id', 'prerequisite_id');
    }


    public function prerequisiteFor()
    {
        return $this->belongsToMany(Course::class, 'course_prerequisite', 'prerequisite_id', 'course_id');
    }


    public function batches()
    {
        return $this->belongsToMany(Batch::class, 'batch_course_semester')
            ->using(BatchCourseSemester::class)
            ->withPivot('semester_id');
    }

    public function semesters()
    {
        return $this->belongsToMany(Semester::class, 'batch_course_semester')
            ->using(BatchCourseSemester::class)
            ->withPivot('batch_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'course_registrations')
            ->using(CourseRegistration::class)
            ->withPivot('semester_id');
    }
}
