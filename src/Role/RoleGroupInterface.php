<?php


namespace TwinElements\AdminBundle\Role;


interface RoleGroupInterface
{
    /**
     * @return array
     */
    static function getRoles(): array;
}
