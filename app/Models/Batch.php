<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    /** @use HasFactory<\Database\Factories\BatchFactory> */
    use HasFactory;

    protected $fillable = ['name', 'department_id', 'year', 'status'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'batch_course_semester')
            ->using(BatchCourseSemester::class)
            ->withPivot('semester_id');
    }

    public function semesters()
    {
        return $this->belongsToMany(Semester::class, 'batch_course_semester')
            ->using(BatchCourseSemester::class)
            ->withPivot('course_id');
    }
}
