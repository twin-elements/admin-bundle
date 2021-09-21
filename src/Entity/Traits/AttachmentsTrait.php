<?php


namespace TwinElements\AdminBundle\Entity\Traits;


use Doctrine\ORM\Mapping as ORM;

trait AttachmentsTrait
{
    /**
     * @var array|null
     * @ORM\Column(type="array", nullable=true)
     */
    private $attachments = [];

    /**
     * @return array|null
     */
    public function getAttachments(): ?array
    {
        return $this->attachments;
    }

    /**
     * @param array|null $attachments
     */
    public function setAttachments(?array $attachments): void
    {
        $this->attachments = $attachments;
    }
}
