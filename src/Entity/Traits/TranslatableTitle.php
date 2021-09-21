<?php

namespace TwinElements\AdminBundle\Entity\Traits;

trait TranslatableTitle
{
    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->translate(null, false)->getTitle();
    }

    public function setTitle($title): void
    {
        $this->translate(null, false)->setTitle($title);
    }
}
