<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="AcademicYear",
 *     required={"id", "name"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="2024/2025"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-02-05T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-02-05T12:30:00Z")
 * )
 */

class AcademicYear extends Model
{
    /** @use HasFactory<\Database\Factories\AcademicYearFactory> */
    use HasFactory;

    protected $fillable = ['name'];

    public function semesters()
    {
        return $this->hasMany(Semester::class);
    }
}
