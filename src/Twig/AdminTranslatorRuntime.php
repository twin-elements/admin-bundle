<?php

namespace TwinElements\AdminBundle\Twig;

use Twig\Extension\RuntimeExtensionInterface;
use TwinElements\Component\AdminTranslator\AdminTranslator;

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

    public function translate(string $key, array $params = [], $domain = null)
    {
        return $this->translator->translate($key, $params, $domain);
    }
}
