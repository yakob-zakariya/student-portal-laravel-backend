<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['batch_id', 'user_id', 'section_id'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_registrations')
            ->using(CourseRegistration::class)
            ->withPivot(['semester_id', 'section_id']);
    }
}
