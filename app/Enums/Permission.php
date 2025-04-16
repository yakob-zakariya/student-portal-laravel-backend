<?php

namespace App\Enums;


enum Permission: string
{
    case CREATE_ROLE = "create-role";
    case UPDATE_ROLE = "update-role";
    case DELETE_ROLE = 'delete-role';
    case VIEW_ROLE = 'view-role';

    case VIEW_PERMISSION = 'view-permission';
    case ASSIGN_PERMISSION =  'assign-permission';
    case REVOVE_PERMISSION = 'revoke-permission';

    case CREATE_DEPARTMENT = 'create-department';
    case UPDATE_DEPARTMENT = 'update-department';
    case DELETE_DEPARTMENT = 'delete-department';
    case VIEW_DEPARTMENT = 'view-department';





    case CREATE_REGISTRAR_USER = 'create-registrar-user';
    case UPDATE_REGISTRAR_USER = 'update-registrar-user';
    case DELETE_REGISTRAR_USER = 'delete-registrar-user';
    case VIEW_REGISTRAR_USER = 'view-registrar-user';

    case CREATE_COORDINATOR_USER = 'create-coordinator-user';
    case UPDATE_COORDINATOR_USER = 'update-coordinator-user';
    case DELETE_COORDINATOR_USER = 'delete-coordinator-user';
    case VIEW_COORDINATOR_USER = 'view-coordinator-user';

    case CREATE_TEACHER_USER = 'create-teacher-user';
    case UPDATE_TEACHER_USER = 'update-teacher-user';
    case DELETE_TEACHER_USER = 'delete-teacher-user';
    case VIEW_TEACHER_USER = 'view-teacher-user';

    case CREATE_STUDENT_USER = 'create-student-user';
    case UPDATE_STUDENT_USER = 'update-student-user';
    case DELETE_STUDENT_USER = 'delete-student-user';
    case VIEW_STUDENT_USER = 'view-student-user';







    case CREATE_COURSE = 'create-course';
    case UPDATE_COURSE = 'update-course';
    case DELETE_COURSE = 'delete-course';
    case VIEW_COURSE = 'view-course';

    case CREATE_ACADEMIC_YEAR = 'create-academic-year';
    case UPDATE_ACADEMIC_YEAR = 'update-academic-year';
    case DELETE_ACADEMIC_YEAR = 'delete-academic-year';
    case VIEW_ACADEMIC_YEAR = 'view-academic-year';

    case CREATE_SEMESTER = 'create-semester';
    case UPDATE_SEMESTER = 'update-semester';
    case DELETE_SEMESTER = 'delete-semester';
    case VIEW_SEMESTER = 'view-semester';

    case CREATE_BATCH = 'create-batch';
    case UPDATE_BATCH = "update-batch";
    case DELETE_BATCH = "delete-batch";
    case VIEW_BATCH = 'view-batch';

    case CREATE_SECTION = 'create-section';
    case UPDATE_SECTION = 'update-section';
    case DELETE_SECTION = 'delete-section';
    case VIEW_SECTION = "view-section";


    case ALLOCATE_COURSE = 'allocate-course';
    case DEALLOCATE_COURSE = 'deallocate-course';
    case VIEW_ALLOCATED_COURSE = 'view-allocated-course';
}
