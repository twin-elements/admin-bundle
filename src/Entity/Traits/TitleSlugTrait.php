<?php

namespace TwinElements\AdminBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait TitleSlugTrait
{
    /**
     * @var string|null
     * @ORM\Column(type="string", length=155)
     * @Gedmo\Slug(fields={"title"})
     */
    private $slug;

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }
}
