<?php

namespace TwinElements\AdminBundle\Service;

use Symfony\Contracts\Translation\TranslatorInterface;

class AdminTranslator
{
    /**
     * @var TranslatorInterface $translator
     */
    private $translator;

    /**
     * @var string|null
     */
    private $adminLocale;

    public function __construct(TranslatorInterface $translator, string $adminLocale)
    {
        $this->translator = $translator;
        $this->adminLocale = $adminLocale;
    }

    public function translate(string $key, array $params = [], $domain = null)
    {
        return $this->translator->trans($key, $params, $domain, $this->adminLocale);
    }
}
