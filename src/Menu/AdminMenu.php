<?php
namespace TwinElements\AdminBundle\Menu;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use TwinElements\AdminBundle\Role\AdminUserRole;

/**
 * Class Menu
 * @package App\AdminBundle\Menu
 * @Cache(expires="tomorrow")
 */
class AdminMenu implements AdminMenuInterface
{
    public function getItems()
    {
        return [
            MenuItem::newInstance('cms.dashboard', 'admin_dashboard', [], 0),
            MenuItem::newInstance('cms.translations', 'dictionary_main', ['category' => 'translations'], 30),
            MenuItem::newInstance('cms.settings', 'dictionary_main', ['category' => 'settings'], 30, AdminUserRole::ROLE_ADMIN),
            MenuItem::newInstance('cms.users', 'user_index', [], 50, AdminUserRole::ROLE_ADMIN),
        ];
    }
}
