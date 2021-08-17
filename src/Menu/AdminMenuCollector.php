<?php

namespace TwinElements\AdminBundle\Menu;

use Symfony\Component\Cache\Adapter\AdapterInterface;

class AdminMenuCollector
{
    /**
     * @var MenuItem[]
     */
    private $items = [];

    public function __construct(iterable $adminMenus, AdapterInterface $cache)
    {
        $itemsCache = $cache->getItem('admin_menu_items');
        if (!$itemsCache->isHit()) {
            $items = [];
            /**
             * @var AdminMenuInterface $adminMenu
             */
            foreach ($adminMenus as $adminMenu) {
                foreach ($adminMenu->getItems() as $adminMenuItem) {
                    $items[] = $adminMenuItem;
                }
            }
            $itemsCache->set($items);
            $cache->save($itemsCache);
        }

        $this->items = $itemsCache->get();
    }

    /**
     * @return MenuItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
