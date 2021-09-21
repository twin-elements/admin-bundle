<?php

namespace TwinElements\AdminBundle\Entity\Traits;

trait TranslatableAttachmentsTrait
{
    /**
     * @return array|null
     */
    public function getAttachments(): ?array
    {
        return $this->translate(null, false)->getAttachments();
    }

    /**
     * @param array|null $attachments
     */
    public function setAttachments(?array $attachments): void
    {
        $this->translate(null, false)->setAttachments($attachments);
    }
}
