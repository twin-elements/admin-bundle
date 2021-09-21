<?php

namespace TwinElements\AdminBundle\Entity\Traits;

interface AttachmentsInterface
{
    /**
     * @return array|null
     */
    public function getAttachments(): ?array;

    /**
     * @param array|null $attachments
     */
    public function setAttachments(?array $attachments): void;
}
