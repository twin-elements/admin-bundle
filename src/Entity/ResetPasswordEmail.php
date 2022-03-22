<?php

namespace TwinElements\AdminBundle\Entity;

use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordToken;
use TwinElements\Component\Message\MessageInterface;

class ResetPasswordEmail implements MessageInterface
{
    private string $email;
    private ResetPasswordToken $resetToken;

    public function __construct(string $email, ResetPasswordToken $resetToken)
    {
        $this->email = $email;
        $this->resetToken = $resetToken;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return ResetPasswordToken
     */
    public function getResetToken(): ResetPasswordToken
    {
        return $this->resetToken;
    }
}
