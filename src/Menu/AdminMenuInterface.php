<?php

namespace TwinElements\AdminBundle\Menu;

interface AdminMenuInterface
{
    /**
     * @return MenuItem[]
     */
    public function getItems();
}
