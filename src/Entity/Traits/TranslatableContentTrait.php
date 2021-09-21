<?php

namespace TwinElements\AdminBundle\Entity\Traits;

trait TranslatableContentTrait
{
    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->translate(null, false)->getContent();
    }

    /**
     * @param string|null $content
     */
    public function setContent(?string $content): void
    {
        $this->translate(null, false)->setContent($content);
    }
}
