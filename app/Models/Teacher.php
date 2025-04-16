<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = ['user_id', 'qualification'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedCourses()
    {
        return  $this->hasMany(TeacherAssignment::class, 'teacher_id');
    }
}
