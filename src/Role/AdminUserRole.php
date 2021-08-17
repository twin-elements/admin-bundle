<?php


namespace TwinElements\AdminBundle\Role;


final class AdminUserRole implements RoleGroupInterface
{
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_USER = 'ROLE_USER';

    static function getRoles() :array
    {
        return [self::ROLE_ADMIN, self::ROLE_USER];
    }
}
