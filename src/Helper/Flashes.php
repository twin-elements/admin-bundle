<?php

namespace TwinElements\AdminBundle\Helper;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class Flashes
{
    private $session;
    private $translator;

    public function __construct(SessionInterface $session, TranslatorInterface $translator)
    {
        $this->session = $session;
        $this->translator = $translator;
    }

    public function successMessage($message = null)
    {
        if (null === $message) {
            $message = $this->translator->trans('cms.success_operation');
        }

        $this->session->getFlashBag()->add('success', $message);
    }

    public function errorMessage($message = null)
    {
        if (null === $message) {
            $message = $this->translator->trans('cms.error');
        }

        $this->session->getFlashBag()->add('error', $message);
    }
}
