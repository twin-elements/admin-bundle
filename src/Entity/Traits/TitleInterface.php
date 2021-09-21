<?php

namespace TwinElements\AdminBundle\Entity\Traits;

interface TitleInterface
{
    /**
     * @return string|null
     */
    public function getTitle(): ?string;

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void;
}
