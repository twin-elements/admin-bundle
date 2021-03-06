<?php

namespace TwinElements\AdminBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait ContentTrait
{
    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    protected $content;

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     */
    public function setContent(?string $content): void
    {
        $this->content = $content;
    }
}
