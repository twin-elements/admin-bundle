<?php

namespace TwinElements\AdminBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait TitleTrait
{
    /**
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }
}
