<?php
namespace TwinElements\AdminBundle\Menu;

use TwinElements\AdminBundle\Role\AdminUserRole;

/**
 * Class Menu
 * @package App\AdminBundle\Menu
 */
class AdminMenu implements AdminMenuInterface
{
    public function getItems()
    {
        return [
            MenuItem::newInstance('admin.dashboard', 'admin_dashboard', [], 0),
            MenuItem::newInstance('admin.translations', 'dictionary_main', ['category' => 'translations'], 30),
            MenuItem::newInstance('admin.settings', 'dictionary_main', ['category' => 'settings'], 30, AdminUserRole::ROLE_ADMIN),
            MenuItem::newInstance('admin.users', 'user_index', [], 50, AdminUserRole::ROLE_ADMIN),
        ];
    }
}
