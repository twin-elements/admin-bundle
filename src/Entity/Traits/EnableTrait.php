<?php

namespace TwinElements\AdminBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait EnableTrait
{
    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $enable = false;

    /**
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->enable;
    }

    /**
     * @param bool $enable
     */
    public function setEnable(bool $enable): void
    {
        $this->enable = $enable;
    }
}
