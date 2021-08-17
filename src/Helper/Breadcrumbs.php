<?php

namespace TwinElements\AdminBundle\Helper;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class Breadcrumbs
{
    private $breadcrumbs;
    private $router;

    public function __construct(\WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs $breadcrumbs, RouterInterface $router, string $adminLocale, TranslatorInterface $translator)
    {
        $this->breadcrumbs = $breadcrumbs;
        $this->router = $router;

        $this->breadcrumbs->addItem($translator->trans('cms.dashboard',[], null, $adminLocale), $this->router->generate('admin_dashboard'));
    }

    /**
     * @return \WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs
     */
    public function getBreadcrumbs(): \WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs
    {
        return $this->breadcrumbs;
    }

    public function setItems(array $items)
    {
        if (count($items) == 0) {
            return;
        }

        foreach ($items as $name => $url) {
            $this->breadcrumbs->addItem($name, $url);
        }
    }

    public function addItem(string $name, ?string $url = null){
        if(null === $name){
            return;
        }

        $this->breadcrumbs->addItem($name, $url);
        return $this;
    }
}
