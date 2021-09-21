<?php

namespace TwinElements\AdminBundle\Entity\Traits;

interface EnableInterface
{
    /**
     * @return bool
     */
    public function isEnable(): bool;

    /**
     * @param bool $enable
     */
    public function setEnable(bool $enable): void;
}
