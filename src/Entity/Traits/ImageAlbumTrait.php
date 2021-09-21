<?php

namespace TwinElements\AdminBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait ImageAlbumTrait
{
    /**
     * @var array|null
     * @ORM\Column(type="array", nullable=true)
     */
    private $imageAlbum;

    /**
     * @return array|null
     */
    public function getImageAlbum(): ?array
    {
        return $this->imageAlbum;
    }

    /**
     * @param array|null $imageAlbum
     */
    public function setImageAlbum(?array $imageAlbum): void
    {
        $this->imageAlbum = $imageAlbum;
    }
}
