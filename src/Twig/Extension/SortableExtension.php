<?php

namespace TwinElements\AdminBundle\Twig\Extension;

use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SortableExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sortable_extension';
    }

    public function getFunctions(){
        return [
            new TwigFunction('renderSortable',[$this, 'renderSortable'],[
                'needs_environment' => true,
                'is_safe' => ['html']
            ])
        ];
    }

    public function renderSortable(Environment $environment, string $entity){
        return $environment->render('@TwinElementsAdmin/sortable-js.html.twig',[
            'entity' => $entity
        ]);
    }
}
