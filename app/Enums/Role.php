<?php

namespace App\Enums;

enum Role: string
{
    case SUPER_ADMIN = 'super-admin';
    case ADMIN = 'admin';
    case TEACHER = 'teacher';
    case STUDENT = 'student';
    case REGISTRAR = 'registrar';
    case COORDINATOR = 'coordinator';
}
