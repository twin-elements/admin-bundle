<?php

namespace TwinElements\AdminBundle\Entity\Traits;

interface ImageInterface
{
    /**
     * @return string|null
     */
    public function getImage(): ?string;

    /**
     * @param string|null $image
     */
    public function setImage(?string $image): void;
}
