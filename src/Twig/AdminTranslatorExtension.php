<?php

namespace TwinElements\AdminBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AdminTranslatorExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('translate_admin', [AdminTranslatorRuntime::class, 'translate'])
        ];
    }
}
