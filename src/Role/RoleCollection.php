<?php

namespace TwinElements\AdminBundle\Role;

use Symfony\Component\Cache\Adapter\AdapterInterface;

class RoleCollection
{
    private $roles = [];

    public function __construct(iterable $roleGroups, AdapterInterface $cache)
    {
        $rolesCache = $cache->getItem('roles_list_items');
        if (!$rolesCache->isHit()) {
            $roles = [];
            /**
             * @var RoleGroupInterface $roleGroup
             */
            foreach ($roleGroups as $roleGroup) {
                foreach ($roleGroup::getRoles() as $role) {
                    $roles[] = $role;
                }
            }
            $rolesCache->set($roles);
            $cache->save($rolesCache);
        }

        $this->roles = $rolesCache->get();
    }

    public function getRoles()
    {
        return $this->roles;
    }
}
