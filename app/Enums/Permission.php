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




    case CREATE_COURSE = 'create-course';
    case UPDATE_COURSE = 'update-course';
    case DELETE_COURSE = 'delete-course';
    case VIEW_COURSE = 'view-course';

    case CREATE_ACADEMIC_YEAR = 'create-academic-year';
    case UPDATE_ACADEMIC_YEAR = 'update-academic-year';
    case DELETE_ACADEMIC_YEAR = 'delete-academic-year';
    case VIEW_ACADEMIC_YEAR = 'view-academic-year';
}
