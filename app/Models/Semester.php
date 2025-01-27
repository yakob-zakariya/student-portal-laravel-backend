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
}
