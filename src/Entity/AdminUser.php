<?php

namespace TwinElements\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\BlameableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Blameable\BlameableTrait;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use TwinElements\AdminBundle\Entity\Traits\EnableInterface;
use TwinElements\AdminBundle\Entity\Traits\EnableTrait;
use TwinElements\AdminBundle\Entity\Traits\IdTrait;

/**
 * @ORM\Table(name="admin_user")
 * @ORM\Entity(repositoryClass="TwinElements\AdminBundle\Repository\AdminUserRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class AdminUser implements UserInterface, PasswordAuthenticatedUserInterface, EnableInterface, BlameableInterface, TimestampableInterface
{
    use IdTrait,
        EnableTrait,
        BlameableTrait,
        TimestampableTrait;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @var string|null
     * @Assert\Email()
     * @ORM\Column(name="email", type="string", length=100, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    function __toString(): string
    {
        return $this->username;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->username;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    /**
     * @param string|null $username
     */
    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
