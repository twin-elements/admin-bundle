<?php

namespace TwinElements\AdminBundle\Twig;

use Twig\Extension\RuntimeExtensionInterface;
use TwinElements\AdminBundle\Service\AdminTranslator;

class AdminTranslatorRuntime implements RuntimeExtensionInterface
{
    /**
     * @var AdminTranslator $translator
     */
    private $translator;

    public function __construct(AdminTranslator $translator)
    {
        $this->translator = $translator;
    }

    public function translate(string $key, $domain = null)
    {
        return $this->translator->translate($key, [], $domain);
    }
}
