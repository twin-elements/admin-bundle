<?php

namespace TwinElements\AdminBundle\Model;

use TwinElements\AdminBundle\Helper\Breadcrumbs;
use TwinElements\AdminBundle\Helper\CrudLoggerMessage;
use TwinElements\Component\AdminTranslator\AdminTranslator;
use TwinElements\Component\Flashes\Flashes;

trait CrudControllerTrait
{
    protected Breadcrumbs $breadcrumbs;

    protected Flashes $flashes;

    protected CrudLoggerMessage $crudLogger;

    protected AdminTranslator $adminTranslator;

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
