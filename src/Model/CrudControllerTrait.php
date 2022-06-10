<?php

namespace TwinElements\AdminBundle\Model;

use TwinElements\AdminBundle\Helper\Breadcrumbs;
use TwinElements\Component\AdminTranslator\AdminTranslator;
use TwinElements\Component\CrudLogger\CrudLoggerInterface;
use TwinElements\Component\Flashes\Flashes;

trait CrudControllerTrait
{
    protected Breadcrumbs $breadcrumbs;

    protected Flashes $flashes;

    protected CrudLoggerInterface $crudLogger;

    protected AdminTranslator $adminTranslator;

    public function __construct(
        Breadcrumbs $breadcrumbs,
        Flashes $flashes,
        CrudLoggerInterface $crudLogger,
        AdminTranslator $translator
    )
    {
        $this->breadcrumbs = $breadcrumbs;
        $this->flashes = $flashes;
        $this->crudLogger = $crudLogger;
        $this->adminTranslator = $translator;
    }
}
