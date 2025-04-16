<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AllocatedCoursesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray(Request $request): array
    {
        return [

            'batch' => [
                'id' => $this['batch']->id,
                'name' => $this['batch']->name,
                'year' => $this['batch']->year,
                'status' => $this['batch']->status,
                'department' => [
                    'id' => $this['batch']->department->id,
                    'name' => $this['batch']->department->name,
                ],
            ],
            'semester' => [
                'id' => $this['semester']->id,
                'name' => $this['semester']->name,
                'registration_open' => $this['semester']->registration_open,
                'academic_year' => [
                    'id' => $this['semester']->academicYear->id,
                    'name' => $this['semester']->academicYear->name,
                ],
            ],
            'courses' => $this['courses']->map(function ($course) {
                return [
                    'id' => $course->id,
                    'name' => $course->name,
                    'code' => $course->code,
                    'credit_hour' => $course->credit_hour,
                ];
            }),
        ];
    }
}
