<?php

namespace TwinElements\AdminBundle\Entity\Traits;

interface TitleSlugInterface
{
    /**
     * @return string|null
     */
    public function getSlug(): ?string;
}
