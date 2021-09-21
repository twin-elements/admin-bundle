<?php


namespace TwinElements\AdminBundle\Entity\Traits;


interface PositionInterface
{
    /**
     * @return int|null
     */
    public function getPosition(): ?int;

    /**
     * @param int|null $position
     */
    public function setPosition(?int $position): void;
}
