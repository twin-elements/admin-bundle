<?php

namespace TwinElements\AdminBundle\Entity\Traits;

trait TranslatableImageAlbumTrait
{
    /**
     * @return array|null
     */
    public function getImageAlbum(): ?array
    {
        return $this->translate(null, false)->getImageAlbum();
    }

    /**
     * @param array|null $imageAlbum
     */
    public function setImageAlbum(?array $imageAlbum): void
    {
        $this->translate(null, false)->setImageAlbum($imageAlbum);
    }
}
