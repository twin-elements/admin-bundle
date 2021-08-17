<?php

namespace TwinElements\AdminBundle\Twig\Extension;

class SortableExtension extends \Twig_Extension
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
            new \Twig_SimpleFunction('renderSortable',[$this, 'renderSortable'],[
                'needs_environment' => true,
                'is_safe' => ['html']
            ])
        ];
    }

    public function renderSortable(\Twig_Environment $environment, string $entity){
        return $environment->render('@CoreAdmin/sortable/script_ajax.html.twig',[
            'entity' => $entity
        ]);
    }
}
