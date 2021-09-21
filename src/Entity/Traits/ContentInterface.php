<?php


namespace TwinElements\AdminBundle\Entity\Traits;


interface ContentInterface
{
    /**
     * @return string|null
     */
    public function getContent(): ?string;

    /**
     * @param $content
     */
    public function setContent(?string $content): void;
}
