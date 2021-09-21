<?php

namespace TwinElements\AdminBundle\Entity\Traits;

trait TranslatableTitleSlug
{
    /**
     * @return string|null
     */
    public function getSlug()
    {
        return $this->translate(null, false)->getSlug();
    }
}
