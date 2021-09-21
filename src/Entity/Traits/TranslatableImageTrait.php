<?php

namespace TwinElements\AdminBundle\Entity\Traits;

trait TranslatableImageTrait
{
    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->translate(null, false)->getImage();
    }

    /**
     * @param string|null $image
     */
    public function setImage(?string $image): void
    {
        $this->translate(null, false)->setImage($image);
    }
}
