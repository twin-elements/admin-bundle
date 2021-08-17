<?php

namespace TwinElements\AdminBundle\Model;

use TwinElements\AdminBundle\Helper\Breadcrumbs;
use TwinElements\AdminBundle\Helper\CrudLoggerMessage;
use TwinElements\AdminBundle\Helper\Flashes;
use TwinElements\AdminBundle\Service\AdminTranslator;

trait CrudControllerTrait
{
    protected $breadcrumbs;
    protected $flashes;
    protected $crudLogger;
    protected $adminTranslator;

    /**
     * @param $breadcrumbs
     */
    public function __construct(
        Breadcrumbs $breadcrumbs,
        Flashes $flashes,
        CrudLoggerMessage $crudLogger,
        AdminTranslator $translator
    )
    {
        $this->breadcrumbs = $breadcrumbs;
        $this->flashes = $flashes;
        $this->crudLogger = $crudLogger;
        $this->adminTranslator = $translator;
    }
}
