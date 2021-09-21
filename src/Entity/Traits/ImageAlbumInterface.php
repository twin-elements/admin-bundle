<?php

namespace TwinElements\AdminBundle\Entity\Traits;

interface ImageAlbumInterface
{
    /**
     * @return array|null
     */
    public function getImageAlbum(): ?array;

    /**
     * @param array|null $imageAlbum
     */
    public function setImageAlbum(?array $imageAlbum): void;
}
